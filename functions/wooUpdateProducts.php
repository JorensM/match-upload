<?php

    require_once("curlPost.php");
    require_once(__DIR__."/../api_keys.php");

    /**
     * Calls the WooCommerce /products endpoint
     * 
     * @param array $params key/value pairs of GET parameters
     */
    function wooUpdateProducts(array $products){
        global $WOO_API_USER;
        global $WOO_API_PASS;

        return curlPost(
            "https://www.matchticketshop.com/wp-json/wc/v3/products",
            [
                "create" => [],
                "update" => $products,
                "delte" => []
            ],
            [
                "Content-Type: application/json"
            ],
            $WOO_API_USER,
            $WOO_API_PASS
        );
    }