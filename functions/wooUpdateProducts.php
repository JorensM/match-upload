<?php

    require_once("curlPost.php");
    require_once(__DIR__."/../api_keys.php");

    /**
     * Calls the WooCommerce /products endpoint with POST
     * 
     * @param array (default []) $products_to_create products that will be created
     * @param array (default []) $products_to_update products that will be updated
     * @param array (default []) $products_to_delete product ids that will be deleted
     */
    function wooUpdateProducts(array $products_to_create = [], array $products_to_update = [], array $products_to_delete = []){
        global $WOO_API_USER;
        global $WOO_API_PASS;

        return curlPost(
            "https://www.matchticketshop.com/wp-json/wc/v3/products/batch",
            [
                "create" => $products_to_create,
                "update" => $products_to_update,
                "delete" => $products_to_delete
            ],
            [
                "Content-Type: application/json"
            ],
            $WOO_API_USER,
            $WOO_API_PASS
        );
    }