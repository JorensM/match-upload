<?php

    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

    require_once( $parse_uri[0] . 'wp-load.php' );  

    if(!class_exists('WC_Product_Variable')){
        if(function_exists("plugins_url")){
            include(plugins_url() . '/woocommerce/includes/class-wc-product-variable.php');// adjust the link
        }
        
    }

    require_once("get_variation_by_name.php");
    require_once("generate_match_title.php");
    require_once("product_functions.php");

    //Calculate new stock after qty change in .csv
    function calculate_new_cat_stock($category_number, WC_Product_Variation $variation, WC_Product_Variable $product, $match){
        $previous_stock = intval($variation->get_stock_quantity());

        $previous_qty = intval($product->get_meta("cat" . $category_number . "qty"));

        $new_qty = intval($match["cat_" . $category_number . "_qty"]);

        $new_stock = $new_qty - ($previous_qty - $previous_stock);

        $variation->set_stock_quantity($new_stock);
    }

    function generate_updated_category_variation($product, $category_number, $match){

        //$variation_id = get_variation_id_by_name($product, "Category " . $category_number);

        $variation_name = generate_match_title($match) . " - " . "Category " . $category_number;

        $variation = get_variation_by_name($product, $variation_name);

        // echo "Variation variable: <br><pre>";
        // echo print_r($variation);
        // echo "</pre>";
        

        $is_new_variation = false;

        if($variation === null){
            //echo "Variation " . $category_number . " was null <br>";
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($product->get_id());
            $is_new_variation = true;
        }else{
            //echo "Variation " . $category_number . " already exists";
        }

        //$variation = new WC_Product_Variation()
        //$variation->set_parent_id($product->get_id());

        //Check if price or qty is zero/undefined, and delete/skip if true
        $price = strval($match["cat_" . $category_number . "_price"]);
        $qty = strval($match["cat_" . $category_number . "_qty"]);
        if($price === "0" || $price === null || $price === ""){
            if(!$is_new_variation){
                $variation->delete();
            }
            //$variation->save();
            return;
        }
        if($qty === "0" || $qty === null || $qty === ""){
            if(!$is_new_variation){
                $variation->delete();
            }
            //$variation->save();
            return;
        }

        
        $variation->set_attributes(array(sanitize_title("Seat Category") => "Category " . strval($category_number)));
        //echo "Setting price for variation " . $variation->get_id() . " to " . $match["cat_" . $category_number . "_price"];
        $variation->set_regular_price($match["cat_" . $category_number . "_price"]);
        //$variation->set_manage_stock(true);
        //$variation->set_stock_quantity($match["cat_" . $category_number . "_qty"]);
        calculate_new_cat_stock($category_number, $variation, $product, $match);
        $variation->save();
    }

    

    function update_categories_metadata($product, $match){
        update_category_metadata("1", $product, $match);
        update_category_metadata("2", $product, $match);
        update_category_metadata("3", $product, $match);
        update_category_metadata("4", $product, $match);
    }

    function update_category_metadata($category_number, WC_Product_Variable $product, $match){

        $qty = $match["cat_" . $category_number . "_qty"];

        $product->update_meta_data("cat" . $category_number . "qty", $qty);
    }

    function update_product_from_match($product_id, $match){
        $product = new WC_Product_Variable($product_id);

        $product->set_name("Tickets " . $match["home_club"] . " vs. " . $match["away_club"]);

        //print_r($product->get_variation_attributes());

        set_stadium_image($product, $match);

        //echo "<br>";

        //echo "Variation data: <br>";
        //echo "<pre>";
        //print_r($product->get_available_variations());
        //echo "</pre>";

        generate_updated_category_variation($product, "4", $match);
        generate_updated_category_variation($product, "3", $match);
        generate_updated_category_variation($product, "2", $match);
        generate_updated_category_variation($product, "1", $match);

        update_categories_metadata($product, $match);

        // $variation = new WC_Product_Variation(;
        // $variation->set_parent_id($product->get_id());
        // print_r($variation->get_attributes("view"));
        // echo "<br>";

        $product->save();
    }