<?php

    //require_once("wp_init.php");
    function get_hidden_category_ids(){
        // $hidden_categories = get_categories(array(
        //     "meta_key" => "hide",
        //     "meta_value" => true
        // ));

        $hidden_categories = get_terms(array(
            "taxonomy" => "product_cat",
            "meta_key" => "hide",
        ));

        //$metadata = 

        //echo "<pre>";
        //echo print_r($hidden_categories);
        //echo "</pre>";

        $ids = [];

        foreach($hidden_categories as $cat){
            
            $meta = get_term_meta($cat->term_id);

            //echo "<pre>";
            //print_r($meta);
            //echo $meta["hide"][0] . "<br>";
            //echo "</pre>";

            if(isset($meta["hide"][0]) && $meta["hide"][0] === "true"){
                //echo "Cat is hidden<br>";
                array_push($ids, $cat->term_id);
            }
        }

        return $ids;
    }