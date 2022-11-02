<?php

    session_start();
    $_SESSION["progress_data"] = [
        "index" =>0,
        "title" => "",
        "new" => false,
        "started" => true,
        "finished" => false,
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

    $logs_file = fopen("logs.txt", "w");

    foreach($matches_arr as $index => $match){

        session_start();

        //if($index > 200){
            //break;
        //}

        if($_SESSION["cancel_upload"] === true){
            break;
        }

        $new = false;        

        if(product_exists($match)){
            update_product_from_match(wc_get_product_id_by_sku($match["id"]), $match);
        }else{
            $new = true;
            create_product_from_match($match);
        }

        $new_string = $new ? "Match doesn't exist, adding..." : "Match already exists, updating...";

        $log_string = 
            $index . "/" . count($matches_arr) . "<br>"
            . generate_match_title($match) . " - " . $match["match_date"] . "<br>"
            . $new_string . "<br><br>";
        fwrite($logs_file, $log_string);

        $_SESSION["progress_data"] = [
            "index" => $index . "/" . count($matches_arr),
            "title" => generate_match_title($match) . " - " . $match["match_date"],
            "new" => $new,
            "started" => true,
            "finished" => false
        ];
        session_write_close();
        //ob_flush(); 
        //flush(); 

        //create_product_from_match($match);
    }
    fclose($logs_file);

    session_start();
    $_SESSION["cancel_upload"] = false;
    $_SESSION["progress_data"] = [
        "index" => 0,
        "title" => "",
        "new" => false,
        "started" => false,
        "finished" => true
    ];
    session_write_close();

    echo json_encode("success");