<?php

    require_once("wp_init.php");

    $products = wc_get_products(array("limit" => 10));

    echo "<pre>";
    echo print_r($products);
    echo "</pre>";