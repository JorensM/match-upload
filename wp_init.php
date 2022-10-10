<?php

    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

    require_once( $parse_uri[0] . 'wp-load.php' );  

    if(!class_exists('WC_Product_Variable')){
        if(function_exists("plugins_url")){
            include(plugins_url() . '/woocommerce/includes/class-wc-product-variable.php');// adjust the link
        }
        
    }