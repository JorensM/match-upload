<?php

    //Core
    require_once(__DIR__."/../../wp_init.php");

    /**
     * Get products that have missing data
     * 
     * @param WC_Product[] array of products to check
     * 
     * @return array missing data
     */
    function getMissingProductData(array $products){
        //Output
        $missing_data_products = [];

        //Loop through each products
        foreach($products as $product){

            //Extract data to check into variables
            $title = $product->get_title();
            $match_time = $product->get_meta("match-time");
            $match_date = $product->get_meta("match-date");
            $match_location = $product->get_meta("match-location");
            $match_championship = $product->get_meta("championship-name");

            //Missing data for product
            $missing_data = [];

            //Check each extracted variable to see if it is undefined or empty.
            //If it is, then add that data to $missing_data
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

            //If There is any missing data, add an entry in the
            //output array
            if(count($missing_data) > 0){
                array_push($missing_data_products, array("title" => $title, "data" => $missing_data));
            }

        }

        return $missing_data_products;
    }
        