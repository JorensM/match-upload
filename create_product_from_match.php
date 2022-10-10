<?php

    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

    require_once( $parse_uri[0] . 'wp-load.php' );  

    if(!class_exists('WC_Product_Variable')){
        if(function_exists("plugins_url")){
            include(plugins_url() . '/woocommerce/includes/class-wc-product-variable.php');// adjust the link
        }
        
    }

    function generate_new_category_variation($product, $category_number, $match){
        //Check if price or qty is zero/undefined, and skip if true
        $price = strval($match["cat_" . $category_number . "_price"]);
        $qty = strval($match["cat_" . $category_number . "_qty"]);
        if($price === "0" || $price === null || $price === ""){
            return;
        }
        if($qty === "0" || $qty === null || $qty === ""){
            return;
        }

        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product->get_id());
        $variation->set_attributes(array(sanitize_title("Seat Category") => "Category " . strval($category_number)));
        $variation->set_regular_price($match["cat_" . $category_number . "_price"]);
        $variation->set_manage_stock(true);
        $variation->set_stock_quantity($match["cat_" . $category_number . "_qty"]);
        $variation->set_name("Category " . strval($category_number));
        
        //$variation->set_id(intval($category_number));
        $variation->save();

        echo "Variation id: <br>";
        echo $variation->get_id();
        echo "<br>";
    }

    function create_product_from_match($match){
        $product = new WC_Product_Variable();

        $home_club = $match["home_club"];
        $away_club = $match["away_club"];

        $product->set_name(generate_match_title($match));
        $product->set_sku($match["id"]);
        $product->set_description("Buy tickets for the football match " .  $home_club . " vs. " . $away_club . " and enjoy this exciting game!");
        //$product->set_manage_stock(true);

        $attributes = [];

        $attribute = new WC_Product_Attribute();
        $attribute->set_name("Seat Category");
        $attribute->set_options(
            array(
                "Category 1",
                "Category 2",
                "Category 3",
                "Category 4"
            )
        );
        $attribute->set_variation(true);
        // $attribute->set_position(0);
        $attribute->set_visible(true);

        // echo "Attribute slugs: <br>";
        // print_r($attribute->get_slugs());
        // echo "<br>";
        // echo "Attribute taxonomy: <br>";
        // print_r($attribute->get_taxonomy());
        // echo "<br>Attribute name:<br>";
        // echo $attribute->get_name();

        $attributes[] = $attribute;

        // $attribute = new WC_Product_Attribute();
        // $attribute->set_name("test");
        // $attribute->set_options(
        //     array(
        //         "123",
        //         "321",
        //     )
        // );
        // $attribute->set_variation(true);
        // // $attribute->set_position(1);
        // $attribute->set_visible(true);

        // $attributes[] = $attribute;

        $product->set_attributes($attributes);

        $product->save();

        generate_new_category_variation($product, "4", $match);
        generate_new_category_variation($product, "3", $match);
        generate_new_category_variation($product, "2", $match);
        generate_new_category_variation($product, "1", $match);

        // $variation = new WC_Product_Variation();
        // $variation->set_parent_id($product->get_id());
        // $variation->set_attributes(array("seat_category" => "4"));
        // $variation->set_regular_price($match["cat_4_price"]);
        // $variation->set_stock_quantity($match["cat_4_qty"]);
        // $variation->save();

        // $variation = new WC_Product_Variation();
        // $variation->set_parent_id($product->get_id());
        // $variation->set_attributes(array("seat_category" => "3"));
        // $variation->set_regular_price($match["cat_3_price"]);
        // $variation->save();

        // $variation = new WC_Product_Variation();
        // $variation->set_parent_id($product->get_id());
        // $variation->set_attributes(array("seat_category" => "2"));
        // $variation->set_regular_price($match["cat_2_price"]);
        // $variation->save();
        
        // $variation = new WC_Product_Variation();
        // $variation->set_parent_id($product->get_id());
        // $variation->set_attributes(array("seat_category" => "1"));
        // $variation->set_regular_price($match["cat_1_price"]);
        // $variation->save();

        $product->save();

        
    }