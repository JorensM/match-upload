<?php

    //session_start();

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

    foreach($matches_arr as $index => $match){

        session_start();

        //if($index > 200){
            //break;
        //}

        $new = false;        

        if(product_exists($match)){
            update_product_from_match(wc_get_product_id_by_sku($match["id"]), $match);
        }else{
            $new = true;
            create_product_from_match($match);
        }

        $_SESSION["progress_data"] = [
            "index" => $index . "/" . count($matches_arr),
            "title" => generate_match_title($match) . " - " . $match["match_date"],
            "new" => $new
        ];

        session_write_close();
        //ob_flush(); 
        //flush(); 

        //create_product_from_match($match);
    }

    echo json_encode("success");