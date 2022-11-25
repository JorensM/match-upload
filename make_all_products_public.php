<?php

    require_once("wp_init.php");

    $products = wc_get_products(array("limit" => -1));

    foreach($products as $product){

        $product_id = $product->get_id();

        wp_update_post( array( 'ID' => $product_id, 'post_status' => 'publish' ));
    }

    echo "All product made public!";