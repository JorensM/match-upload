<?php
    
    //require_once("wp_init.php");

    //Functions
    require_once(__DIR__."/../functions/getMissingProductData.php");


    $products = wc_get_products(array("limit" => -1));

    $missing_data_products = getMissingProductData($products);

    
    //Check if there is any missing data
    if(count($missing_data_products) > 0){
        //If there is missing data, then loop through each entry
        //and output which data is missing
        foreach($missing_data_products as $product){
            echo $product["title"] . "<br>";
            foreach($product["data"] as $entry){
                echo $entry;
            }
            echo "<br>";
            echo "---------------<br>";
        }
    }
    else{
        //If there is no missing data, output message
        echo "No missing data found";
    }
    