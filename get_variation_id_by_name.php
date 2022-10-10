<?php

    function get_variation_id_by_name(WC_Product_Variable $product, $variation_name){
        $variations = $product->get_available_variations();
        foreach($variations as $variation){
            if($variation->get_name() === $variation_name){
                return $variation->get_id();
            }
        }
        return null;
    }