<?php

    //Checks if a product belongs to a category that is hidden
    function has_product_hidden_category($product){

        $category_ids = $product->get_category_ids();

        $has_hidden_categories = false;

        foreach($category_ids as $cat_id){
            $term = get_term_by("id", $cat_id, 'product_cat');
            $term_id = $term->term_id;
            $term_slug = $term->slug;
            $is_cat_hidden = get_term_meta($term_id, "hide")[0];
            if($is_cat_hidden){
                $has_hidden_categories = true;
            }
        }
        

    }