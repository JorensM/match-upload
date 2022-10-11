<?php

    require_once("wp_init.php");

    //$products = wc_get_products(array("limit" => 10));

    echo "hello";

    $data = array(
        'field1' => 'field1value',
        'field2' => 'field2value',
    );
    
    $ch = curl_init("https://www.matchticketshop.com/wp-json/wc/v3/products");
    //curl_setopt($ch, CURLOPT_POST, true);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $public_key = "ck_3a8304f13c13bde4ade25d749ebd4227034877f0";
    $private_key = "cs_4ee08ab9223aaee0060de1c4924d7a02449f99e7";
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json'
        ,"Authorization: Basic ".base64_encode($public_key.":".$private_key)
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $products = curl_exec($ch);
    //return json_decode($resultStr, true);

    $output = json_decode($products, true);

    echo "<pre>";
    echo print_r($output);
    echo "</pre>";