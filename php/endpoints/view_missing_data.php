<?php
    
    require_once("wp_init.php");

    $products = wc_get_products(array("limit" => -1));

    $missing_data_products = [];

    foreach($products as $product){

        $title = $product->get_title();
        $match_time = $product->get_meta("match-time");
        $match_date = $product->get_meta("match-date");
        $match_location = $product->get_meta("match-location");
        $match_championship = $product->get_meta("championship-name");

        $missing_data = [];

        if($match_time === null || $match_time === ""){
            array_push($missing_data, "Time");
        }

        if($match_date === null || $match_date === ""){
            array_push($missing_data, "Date");
        }

        if($match_location === null || $match_location === ""){
            array_push($missing_data, "Location");
        }

        if($match_championship === null || $match_championship === ""){
            array_push($missing_data, "Championship name");
        }

        if(count($missing_data) > 0){
            array_push($missing_data_products, array("title" => $title, "data" => $missing_data));
        }

    }

    if(count($missing_data_products) > 0){
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
        echo "No missing data found";
    }
    