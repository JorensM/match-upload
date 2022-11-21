<?php

    require_once("wp_init.php");

    $products = wc_get_products(array("limit" => -1));

    $public_products = 0;
    $private_products = 0;

    foreach($products as $product){
        $hide = false;
        $category_ids = $product->get_category_ids();

        //echo "<pre>";
        //echo $product->get_title();
        //echo "</pre><br>";

        foreach($category_ids as $category_id){
            //echo $category_id . "<br>";
            $term = get_term_by("id", $category_id, 'product_cat');
            $term_id = $term->term_id;
            $is_cat_hidden = get_term_meta($term_id, "hide")[0];

            //echo "hidden: " . $is_cat_hidden . "<br>";
            //echo "type: " . gettype($is_cat_hidden) . "<br>";

            if($is_cat_hidden === "true"){
                $hide = true;
                //echo "Category" . $category_id . " is hidden<br>";
                //echo "Name: " . $term->name ."<br>";
                break;
            }
            //echo "<pre>";
            //print_r($term);
            //print_r($is_cat_hidden);
            //echo "</pre>";
        }

        $product_id = $product->get_id();
        
        if($hide){
            wp_update_post( array( 'ID' => $product_id, 'post_status' => 'private' ));
            //echo "Setting " . $product->get_title() . " to private<br>";
            $private_products += 1;
        }else{
            //echo "test<br>";
            wp_update_post( array( 'ID' => $product_id, 'post_status' => 'publish' ));
            //echo "Setting " . $product->get_title() . " to public<br>";
            $public_products +=1;
        }
    }