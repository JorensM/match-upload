<?php

    require_once("curlGet.php");
    require_once(__DIR__."/../api_keys.php");

    /**
     * Calls the WooCommerce /products endpoint
     * 
     * @param array $params key/value pairs of GET parameters
     */
    function wooGetVariations($product_id){
        global $WOO_API_USER;
        global $WOO_API_PASS;

        return curlGet(
            "https://www.matchticketshop.com/wp-json/wc/v3/products/$product_id/variations",
            $params = [],
            $WOO_API_USER,
            $WOO_API_PASS
        );
    }