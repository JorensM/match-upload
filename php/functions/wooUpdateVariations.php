<?php

    require_once("curlPost.php");
    require_once(__DIR__."/../../const.php");

    /**
     * Calls the WooCommerce /products endpoint
     * 
     * @param array $params key/value pairs of GET parameters
     */
    function wooUpdateVariations($product_id, array $variations_to_create, array $variations_to_update, array $variations_to_delete){
        global $WOO_API_USER;
        global $WOO_API_PASS;

        return curlPost(
            "https://www.matchticketshop.com/wp-json/wc/v3/products/$product_id/variations/batch",
            [
                "create" => $variations_to_create,
                "update" => $variations_to_update,
                "delete" => $variations_to_delete
            ],
            [
                "Content-Type: application/json"
            ],
            $WOO_API_USER,
            $WOO_API_PASS
        );
    }