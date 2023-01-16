<?php

    require_once(__DIR__."/../classes/MatchObject.php");

    function matchObjectToProductArray(MatchObject $matchObject){

        $product_description = $matchObject->generateDescription();

        $output_array = [
            "title" => $matchObject->generateMatchTitle(),
            "description" => $product_description,
            "category_ids" => $matchObject->generateCategoryIds(),
            "image_id" => $matchObject->generateStadiumImageId(),
            "variations" => $matchObject->generateVariationData(),
            "sku" => $matchObject->get("id"),
            "meta_data" => [
                "1st-team-image" => null,
                "2nd-team-image" => null,
                "tickets_products-page-short-details" => $product_description,
                "match-date" => $matchObject->get("match_date"),
                "match-location" => $matchObject->get("stadium"),
                "championship-name" => $matchObject->get("tournament"),
                "date-confirm" => $matchObject->get("match_fixed"),
                "match-time" => $matchObject->get("match_time")

            ]
        ];

        return $output_array;

    }