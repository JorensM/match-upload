<?php

    require_once("wp_init.php");
    require_once("hide_product_if_outdated.php");

    $products = wc_get_products(array("limit" => -1));

    foreach($products as $product){
        hide_product_if_outdated($product);
    }

    echo "outdated matches have been hidden!";