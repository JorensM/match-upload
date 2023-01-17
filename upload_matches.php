<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("classes/SessionDataManager.php");
    require_once("classes/CsvToIDataConverter.php");
    require_once("classes/UploadsManager.php");
    require_once("classes/MatchObject.php");
    require_once("classes/MatchObjectToProductImporter.php");
    require_once("classes/MTSLogger.php");
    require_once("classes/ProductImporter.php");

    require_once("classes/enum/EnumSessionDataElement.php");

    require_once("functions/matchObjectToProductArrayMany.php");

    //echo "hello";

    

    $write_logs = isset($_POST["write-logs"]) && !($_POST["write-logs"] === "false" || $_POST["write-logs"] === "0");

    $logs_file_handle = $write_logs ? fopen("logs.txt", "w") : null;
    
    $logger = new MTSLogger($logs_file_handle);

    $logger->log("Test");

    $session = new SessionDataManager();

    $session->set(
        EnumSessionDataElement::ProgressData,
        [
            "index" => 0,
            "title" => "",
            "message" => "",
            "new" => false,
            "started" => true,
            "finished" => false,
            "start_time" => time(),
            "end_time" => 0,
            "error" => false,
            "error_message" => ""
        ]
    );

    function error(Exception $e, int $status_code = 400){
        global $session;

        $e_message = $e->getMessage();

        http_response_code($status_code);
        echo json_encode(["error" => $e_message]);
        $session->setEntries(
            EnumSessionDataElement::ProgressData, 
            [
                "error" => true,
                "error_message" => $e_message
            ]
        );

        die();
        //throw $e;
    }

    

    //error(new Exception("hello"), 400);

    // class ProgressData {
    //     public int $index;
    //     public string $title;
    //     public bool $new;
    //     public bool $started;
    //     public bool $finished;
    //     public int $start_time;
    //     public int $end_time;
    //     public bool $error = false;
    //     public string $error_message = "";

    //     public function __construct(
    //         int $index = 0,
    //         string $title = "",
    //         bool $new = false,
    //         bool $started = true,
    //         bool $finished = false,
    //         int $start_time = time(),
    //         int $end_time = 0,
    //         bool $error = false,
    //         string $error_message = ""
    //     ){
    //         $this->$index = $index;
    //         $this->$title = $title;
    //         $this->$new = $new;
    //         $this->$started = $started;
    //         $this->$finished = $finished;
    //         $this->$start_time = $start_time;
    //         $this->$end_time = $end_time;
    //         $this->$error = $error;
    //         $this->$error_message = $error_message;
    //     }
    // }

    interface IUploadsManager {

        /**
         * Validates file based on params. Validates whether file was uploaded. Throws error if validation failed.
         * $params example:
         *      [
         *          "ext" => [ //These are the file extensions against which to validate
         *              "png"
         *              "gif"
         *              "jpg"
         *           ]
         *      ]
         * @param array $params associative array of params
         * 
         */
        public function validateFile(string $name, array $params = null);


        /**
         * Wrapper function for fopen();
         * 
         * @param string $name name of uploaded file
         * @param string $mode default "r" access mode, for example "r" or "w"
         * 
         * @return resource returns file handle (stream)
         */
        public function openFile(string $name, string $mode = "r");
        
        /**
         * Wrapper function for fclose()
         * 
         * @param any $handle file handle (stream) that was returned by openFile
         * 
         * @return bool returns the return value of fclose()
         */
        public function closeFile($handle);
    }

    //error(new MyException("hello"));

    $logger->log("1");
    
    //Uploads/files
    $uploadsManager = new UploadsManager();

    $matches_file_name = "matches-file";
    $matches_file_handle;

    //Converters

    //Match data keys to csv columns mappings
    $csvToMatchMappings = [
        "home_club" => 0,
        "away_club" => 1,
        "stadium" => 2,
        "tournament" => 3,
        "match_date" => 4,
        "match_time" => 5,
        "match_fixed" => 6,
        "ticket_only_discount" => 7,
        "cat_1_qty" => 8,
        "cat_1_price" => 9,
        "cat_2_qty" => 10,
        "cat_2_price" => 11,
        "cat_3_qty" => 12,
        "cat_3_price" => 13,
        "cat_4_qty" => 14,
        "cat_4_price" => 15,
        "hotel_price" => 16,
        "package" => 17,
        "description" => 18,
        "available_before_days" => 19,
        "id" => 20
    ];

    //Convert $csvToMatchMappings to an assoc. array with empty values.
    //This is for the $match_object_prototype which is required for the $csvToMatch converter.
    $match_object_prototype_data = [];
    foreach($match_object_prototype_data as $key => $value){
        $match_object_prototype_data[$key] = null;
    }

    $match_object_prototype = new MatchObject($match_object_prototype_data);

    $csvToMatch = new CsvToIDataConverter(
        [
            "key_to_column_mappings" => $csvToMatchMappings,
            "data_object_prototype" => $match_object_prototype
        ]
    );

    $logger->log("2");

    $matchToProduct = new MatchObjectToProductImporter([
        "limit" => 10,
        "batch_size" => 80,
        "session" => $session,
        "logger" => $logger,
    ]);

    $productImporter = new ProductImporter([
        "limit" => 40,
        "batch_size" => 80,
        "session" => $session,
        "logger" => $logger,
        "importBy" => "sku"
    ]);

    
    
    $logger->log("Before");
    //Validate file and create file stream
    try {
        $uploadsManager->validateFile($matches_file_name, [
            "ext" => [
                "csv"
            ]
        ]);

        $matches_file_handle = $uploadsManager->openFile($matches_file_name);
    }catch(Exception $e){
        error(new MyException("Could not validate file: " . $e->getMessage()));
        //throw new MyException("Could not validate file: " . $e->getMessage());
    }

    $logger->log("After");

    //Convert file into array MatchObjects
    try {
        $csvToMatch->convert($matches_file_handle);
    }catch(Exception $e){
        error(new MyException("Error converting file: " . $e->getMessage()));
    }

    $logger->log("Next");

    $matches_arr = $csvToMatch->getResult();

    //Import MatchObjects array into WooCommerce products

    // try {
    //     $matchToProduct->import($matches_arr);
    // }catch(Exception $e){
    //     error(new MyException("Error importing matches: " . $e->getMessage()));
    // }

    //MatcheObjects converted into arrays supported by ProductImporter
    $matches_products_arr = matchObjectToProductArrayMany($matches_arr, 40);

    //printRPre(json_encode($matches_products_arr));

    try {
        $productImporter->import($matches_products_arr);
    }catch(Exception $e){
        error(new MyException("Error importing matches: " . $e->getMessage()));
    }

    //printRPre($matches_arr);

    //error(new MyException("hello2"));

    ini_set('max_execution_time', 0);

    $show_info = false;

    $info = $show_info ? print_r($matches_arr, true) : "";

    // echo "<pre>";
    // print_r($matches_arr);
    // echo "</pre>";

    $logger->close();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["success" => true, "info" => $info]);
    