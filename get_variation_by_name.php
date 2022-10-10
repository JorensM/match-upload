<?php


$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

require_once( $parse_uri[0] . 'wp-load.php' );  

if(!class_exists('WC_Product_Variable')){
    if(function_exists("plugins_url")){
        include(plugins_url() . '/woocommerce/includes/class-wc-product-variable.php');// adjust the link
    }
    
}

if(!class_exists('WC_Product_Variation')){
    if(function_exists("plugins_url")){
        include(plugins_url() . '/woocommerce/includes/class-wc-product-variation.php');// adjust the link
    }
    
}

require_once("generate_match_title.php");

function get_variation_by_name(WC_Product_Variable $product, $variation_name){
    $variations = $product->get_available_variations("objects");
    foreach($variations as $variation){
        // echo "Getting variation name: <br>";
        // echo "<pre>";
        // echo print_r($variation);
        // echo "</pre>";
        // echo $variation->get_name() . "<br>";
        //echo "Checking if variation name (" . $variation->get_name() . ") equals (" . $variation_name . ")<br>";
        if($variation->get_name() === $variation_name){
            return $variation;
        }
    }
    return null;
}