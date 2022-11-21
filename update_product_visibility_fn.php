<?php



    function update_product_visiblity($cat_id){

        $term = get_term_by("id", $cat_id, 'product_cat');
        $term_id = $term->term_id;
        $term_slug = $term->slug;
        $is_cat_hidden = get_term_meta($term_id, "hide")[0];

        $products = wc_get_products(array(
            "limit" => -1,
            'category' => array($term_slug),
        ));


        foreach($products as $product){
            $product_id = $product->get_id();

            if($is_cat_hidden === "true"){
                wp_update_post( array( 'ID' => $product_id, 'post_status' => 'private' ));
            }else{
                wp_update_post( array( 'ID' => $product_id, 'post_status' => 'publish' ));
            }
        }
        
    }