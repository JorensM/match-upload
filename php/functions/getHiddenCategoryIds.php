<?php

    /**
     * Get category ids that have meta field "hide" set to true
     * 
     * @return int[] array of category ids
     */
    function getHiddenCategoryIds(){

        //Retrieve categories
        $hidden_categories = get_terms(array(
            "taxonomy" => "product_cat",
            "meta_key" => "hide",
        ));

        $ids = [];

        foreach($hidden_categories as $cat){
            
            $meta = get_term_meta($cat->term_id);

            if(isset($meta["hide"][0]) && $meta["hide"][0] === "true"){
                //echo "Cat is hidden<br>";
                array_push($ids, $cat->term_id);
            }
        }

        return $ids;
    }