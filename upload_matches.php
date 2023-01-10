<?php

    require("classes/SessionDataManager.php");

    echo "hello";

    enum SessionElement {
        const ProgressData = "progress_data";
    }

    function error(Exception $e, int $status_code = 400){
        http_response_code($status_code);
        echo json_encode(["error" => $e->getMessage()]);

        //die();
        //throw $e;
    }

    error(new Exception("hello"), 400);

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

    class UploadsManager {

        /**
         * Manages files uploaded by POST
         */
        
        /**
         * 
         */
        public function validateFile(string $name, array $params = null){
            $file_exists = array_key_exists($name, $_FILES);
            if(!$file_exists){
                $e_message = "Could not find file";
                throw new MyException($e_message, $this, __METHOD__);
            }
            if($file_exists){
                if ($_FILES[$name]['error'] === UPLOAD_ERR_OK) {
                    if($params){
                        if(array_key_exists("ext", $params)){
                            $filename = $_FILES[$name]["name"];
                            $ext = pathinfo($filename, PATHINFO_EXTENSION);

                            $ext_valid = in_array($ext, $params["ext"]);

                            if(!$ext_valid){
                                $e_message = "Invalid filetype: $ext given, expected " . implode("/",$params["ext"]);
                                throw new MyException($e_message, $this, __METHOD__);
                            }
                        }
                    }
                    return true;
                } else {
                    $e_message = "Upload failed with error code " . $_FILES[$name]['error'];
                    throw new MyException($e_message, $this, __METHOD__);
                }
            }
        }

        public function openFile(string $name, string $mode = "r"){
            $file_handle = fopen($_FILES[$name]["tmp_name"], $mode);

            return $file_handle;
        }

        public function closeFile($handle){
            return fclose($handle);
        }
        
    }

    $session = new SessionDataManager();

    $session->set(
        SessionElement::ProgressData,
        [
            "index" => 0,
            "title" => "",
            "new" => false,
            "started" => true,
            "finished" => false,
            "start_time" => time(),
            "end_time" => 0,
            "error" => false,
            "error_message" => ""
        ]
    );

    $fileManager = new UploadsManager();

    try{
        $fileManager->validateFile("matches-file", [
            "ext" => [
                "csv"
            ]
        ]);
    }catch(Exception $e){
        throw new MyException("Could not validate file: " . $e->getMessage());
    }

    ini_set('max_execution_time', 0);

    $write_logs = isset($_POST["write-logs"]) && !($_POST["write-logs"] === "false" || $_POST["write-logs" === "0"]);

    // if(isset($_POST["write-logs"])){
    //     $write_logs = ($_POST["write-logs"] === 'false' || $_POST["write-logs"] === '0') ? false : true; 
    // }