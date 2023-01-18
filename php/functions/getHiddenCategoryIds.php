<?php

    /**
     * Get category ids that have meta field "hide" set to true
     * 
     * @return int[] array of category ids
     */
    function getHiddenCategoryIds(){

        //Retrieve all categories
        $all_categories = get_terms(array(
            "taxonomy" => "product_cat",
        ));

        //Ids array that will be returned
        $ids = [];

        //Loop through each category
        foreach($all_categories as $cat){
            
            //Retrieve meta data of category
            $meta = get_term_meta($cat->term_id);

            //Check if "hide" meta field is set to true
            if(isset($meta["hide"][0]) && $meta["hide"][0] === "true"){
                //If "hide" is set to true, add the current category's id to $ids
                array_push($ids, $cat->term_id);
            }
        }

        return $ids;
    }