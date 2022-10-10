<?php

    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

    require_once( $parse_uri[0] . 'wp-load.php' ); 

    require_once("generate_match_title.php");

    function product_exists($match){
        $match_title = generate_match_title($match);

        $product_id = wc_get_product_id_by_sku($match["id"]);

        

        if($product_id === 0 ){
            return false;
        }else{
            echo "Product id: " . $product_id . "<br>";
            return true;
        }
    }