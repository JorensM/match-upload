<?php

    session_start();
    $_SESSION["progress_data"] = [
        "index" =>0,
        "title" => "",
        "new" => false,
        "started" => true,
        "finished" => false,
        "start_time" => time(),
        "end_time" => 0
    ];
    $_SESSION["cancel_upload"] = false;
    session_write_close();

    ini_set('max_execution_time', 0);

    require_once("matches_csv_to_arr.php");
    require_once("create_product_from_match.php");
    require_once("update_product_from_match.php");
    require_once("product_exists.php");
    require_once("generate_match_title.php");

    //echo json_encode("test");

    $write_logs = false;
    if(isset($_POST["write-logs"])){
        $write_logs = ($_POST["write-logs"] === 'false' || $_POST["write-logs"] === '0') ? false : true; 
    }

    if(array_key_exists('matches-file', $_FILES)){
        if ($_FILES['matches-file']['error'] === UPLOAD_ERR_OK) {
           
        } else {
           die("Upload failed with error code " . $_FILES['file']['error']);
        }
    }

    $file_handle = fopen($_FILES["matches-file"]["tmp_name"], "r");

    if(!$file_handle){
        die("Failed to open file");
    }

    $matches_arr = matches_csv_to_arr($file_handle);

    // print("<pre>");
    // print_r($matches_arr);
    // print("</pre>");

    //echo "test";

    error_log("--------NEW UPLOAD STARTED-------");

    $logs_file = $write_logs ? fopen("logs.txt", "w") : null;

    foreach($matches_arr as $index => $match){

        session_start();

        // if($index > 100){
        //     break;
        // }

        if($_SESSION["cancel_upload"] === true){
            break;
        }

        $new = false;  
        
        $product_log = "";

        if(product_exists($match)){
            $product_log = update_product_from_match(wc_get_product_id_by_sku($match["id"]), $match);
        }else{
            $new = true;
            create_product_from_match($match);
        }

        $new_string = $new ? "Match doesn't exist, adding..." : "Match already exists, updating...";

        $log_string = 
            $index . "/" . count($matches_arr) . "<br>"
            . generate_match_title($match) . " - " . $match["match_date"] . "<br>"
            . $product_log . "<br>abc"
            . $new_string . "<br><br>";
        if($write_logs){
            fwrite($logs_file, $log_string);
        }
        

        $start_time = 0;
        if(isset($_SESSION["progress_data"]["start_time"])){
            $start_time = $_SESSION["progress_data"]["start_time"];
        }

        $_SESSION["progress_data"] = [
            "index" => $index . "/" . count($matches_arr),
            "title" => generate_match_title($match) . " - " . $match["match_date"],
            "new" => $new,
            "started" => true,
            "finished" => false,
            "start_time" => $start_time,
            "end_time" => time()
        ];
        session_write_close();
        //ob_flush(); 
        //flush(); 

        //create_product_from_match($match);
    }
    if($write_logs){
        fclose($logs_file);
    }
    

    session_start();
    $_SESSION["cancel_upload"] = false;
    $start_time = 0;
    if(isset($_SESSION["progress_data"]["start_time"])){
        $start_time = $_SESSION["progress_data"]["start_time"];
    }
    $_SESSION["progress_data"] = [
        "index" => 0,
        "title" => "",
        "new" => false,
        "started" => false,
        "finished" => true,
        "start_time" => $start_time,
        "end_time" => time()
    ];
    session_write_close();

    echo json_encode("success");