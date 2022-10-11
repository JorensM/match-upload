<?php

    echo json_encode(["test",$_FILES]);

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

        if($index > 5){
            break;
        }

        if(product_exists($match)){
            update_product_from_match(wc_get_product_id_by_sku($match["id"]), $match);
        }else{
            create_product_from_match($match);
        }

        //create_product_from_match($match);
    }

    //echo json_encode("success");