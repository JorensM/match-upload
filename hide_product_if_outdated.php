<?php

    require_once("wp_init.php");

function hide_product_if_outdated($product){

    $match_date = $product->get_meta("match-date");

    $match_date_timestamp = strtotime($match_date);
    $current_timestamp = time();

    if($match_date_timestamp > $current_timestamp){

    }

    //Whether the match date is outdated or not.
    $outdated = $match_date_timestamp < $current_timestamp;

    $product_id = $product->get_id();

    if($outdated){
        wp_update_post( array( 'ID' => $product_id, 'post_status' => 'private' ));
    }

}