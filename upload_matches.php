<?php
    //echo var_dump($_FILES);

    require_once("matches_csv_to_arr.php");
    require_once("create_product_from_match.php");
    require_once("update_product_from_match.php");
    require_once("product_exists.php");
    require_once("generate_match_title.php");

    if(array_key_exists('matches-file', $_FILES)){
        if ($_FILES['matches-file']['error'] === UPLOAD_ERR_OK) {
           //echo 'upload was successful';
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

    echo "Generating products...<br>";

    foreach($matches_arr as $index => $match){

        if($index > 5){
            break;
        }

        //echo "<br>-----------<br>";

        //echo generate_match_title($match) . " - " . $match["match_date"] . "<br>";

        if(product_exists($match)){
            //echo "Product already exists, updating...<br>";
            update_product_from_match(wc_get_product_id_by_sku($match["id"]), $match);
        }else{
            //echo "Product doesn't exist, adding...<br>";
            create_product_from_match($match);
        }

        //echo "------------<br>";

        //create_product_from_match($match);
    }

    echo "Products generated, you may now leave this page";

    //echo var_dump($matches_file);