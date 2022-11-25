<?php

    require_once("wp_init.php");

    $products = wc_get_products(array('limit' => -1));

    
    foreach($products as $product){
        echo $product->get_meta("match-date") . "<br>";
    }