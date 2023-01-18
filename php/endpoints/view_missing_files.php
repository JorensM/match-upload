<?php

    require_once("wp_init.php");
    require_once(__DIR__."/../functions/fileExistsOnUrl.php");

    $products = wc_get_products(array("limit" => -1));

    $missing_stadiums = [];
    $missing_teams = [];

    echo "Locating missing files, this will take a minute or two...";

    //Find missing files
    foreach($products as $product){
        //Find missing stadium images
        $img = $product->get_image("woocommerce_thumbnail", null, false);
        if($img === ""){
            $skip = false;
            $stadium_name = $product->get_meta("match-location");
            foreach($missing_stadiums as $missing_stadium){
                if($missing_stadium === $stadium_name){
                    $skip = true;
                }
            }
            if(!$skip){
                array_push($missing_stadiums, $stadium_name);
            }
        }

        //Find missing club images
        $team_1_img = $product->get_meta("1st-team-image");
        $team_2_img = $product->get_meta("2nd-team-image");

        $team_images = [$team_1_img, $team_2_img];

        foreach($team_images as $team_image){
            
            if(!fileExistsOnUrl($team_image)){
                $filename = basename($team_image);
                $skip = false;
                foreach($missing_teams as $missing_team){
                    if($missing_team === $filename){
                        $skip = true;
                    }
                }
                if(!$skip){
                    array_push($missing_teams, $filename);
                }
            }
        }
    }


    //Echo missing stadium files
    echo "<h4>Missing Stadium images</h4>";
    if(count($missing_stadiums) > 0){
        foreach($missing_stadiums as $missing_stadium){
            echo $missing_stadium . "<br>";
        }
    }else{
        echo "No missing files found<br>";
    }

    //Echo missing club files
    echo "<h4>Missing club images</h4>";
    if(count($missing_teams) > 0){
        foreach($missing_teams as $missing_team){
            echo $missing_team . "<br>";
        }
    }else{
        echo "No missing files found<br>";
    }