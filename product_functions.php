<?php

    require_once("get_image_id_of_stadium.php");
    require_once("generate_match_metadata.php");

    //Set stadium image for product
    function set_stadium_image($product, $match){
        //Get image id of stadium
        $stadium_image_id = get_image_id_of_stadium($match["stadium"]);

        //echo "Getting stadium image id: " . $stadium_image_id;

        $product->set_image_id($stadium_image_id);
    }

    //Generate product description
    function generate_product_description($match){
        $home_club = $match["home_club"];
        $away_club = $match["away_club"];

        return "Buy tickets for the football match " .  $home_club . " vs. " . $away_club . " and enjoy this exciting game!";
    }

    //Add/update general metadata to product
    function add_general_metadata(WC_Product_Variable $product, $match, $update = false){
        $metadata = generate_match_metadata($match);

        if(!$update){
            $product->add_meta_data("match-date", $metadata["match-date"]);
            $product->add_meta_data("tickets_products-page-short-details", $metadata["match-description"]);
            //echo "Setting championship name: " . $metadata["match-tournament"] . "<br>";
            $product->add_meta_data("championship-name", $metadata["match-tournament"]);
            $product->add_meta_data("date-confirm", $metadata["match-date-confirm"]);
            $product->add_meta_data("1st-team-image", $metadata["team1-img"]);
            $product->add_meta_data("2nd-team-image", $metadata["team2-img"]);
        }else{
            $product->update_meta_data("match-date", $metadata["match-date"]);
            $product->update_meta_data("tickets_products-page-short-details", $metadata["match-description"]);
            $product->update_meta_data("championship-name", $metadata["match-tournament"]);
            $product->update_meta_data("date-confirm", $metadata["match-date-confirm"]);
            $product->update_meta_data("1st-team-image", $metadata["team1-img"]);
            $product->update_meta_data("2nd-team-image", $metadata["team2-img"]);
        }

        $product->save_meta_data();
    }