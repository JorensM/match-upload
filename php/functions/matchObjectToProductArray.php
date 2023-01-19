<?php

    require_once(__DIR__."/../classes/MatchObject.php");

    /**
     * Generate a single product metafield
     * 
     * @param string $key metafield key
     * @param any $value metafield value
     * 
     * @return array meta field
     */
    function generateMetafield(string $key, $value){
        return [
            "key" => $key,
            "value" => $value
        ];
    }

    function matchObjectToProductArray(MatchObject $matchObject){

        $product_description = $matchObject->generateDescription();
        $team_images = $matchObject->generateTeamImageFilenames();

        $match_date_obj = strtotime($matchObject->get("match_date"));
        $match_date = date("Y-m-d", $match_date_obj);

        $output_array = [
            "enable" => $matchObject->isEnabled(),
            "title" => $matchObject->generateMatchTitle(),
            "description" => $product_description,
            "category_ids" => $matchObject->generateCategoryIds(),
            "image_id" => $matchObject->generateStadiumImageId(),
            "variations" => $matchObject->generateVariationData(),
            "sku" => $matchObject->get("id"),
            "meta_data" => [
                generateMetafield("1st-team-image", $team_images[0]),
                generateMetafield("2nd-team-image", $team_images[1]),
                generateMetafield("tickets_products-page-short-details", $product_description),
                generateMetafield("match-date", $match_date),
                generateMetafield("match-location", $matchObject->get("stadium")),
                generateMetafield("championship-name", $matchObject->get("tournament")),
                generateMetafield("date-confirm", $matchObject->get("match_fixed")),
                generateMetafield("match-time", $matchObject->get("match_time"))
            ]
        ];

        return $output_array;

    }