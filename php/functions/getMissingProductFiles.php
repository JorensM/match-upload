<?php

    require_once("fileExistsOnUrl.php");
    require_once("teamNamesFromTitle.php");

    /**
     * get missing stadium images and club icons for given products
     * 
     * @param WC_Product[] $products products to check
     * 
     * @return array ["missing_stadiums" => [], "missing_teams" => []]
     */
    function getMissingProductFiles(array $products){
        //Output
        $missing_stadiums = [];
        $missing_teams = [];

        //Loop through passed products
        foreach($products as $product){
            //Find missing stadium images
            //Retrive image of product
            $img = $product->get_image("woocommerce_thumbnail", null, false);
            //Check if image is set
            if($img === ""){
                //If image is not set, check if the stadium name for the missing image is already added
                //to the output
                $skip = false;
                $stadium_name = $product->get_meta("match-location");
                foreach($missing_stadiums as $missing_stadium){
                    if($missing_stadium === $stadium_name){
                        $skip = true;
                    }
                }
                if(!$skip){
                    //If stadium name hasn't yet been added to the output, then add it
                    array_push($missing_stadiums, $stadium_name);
                }
            }

            //Find missing club images
            $team_1_img = $product->get_meta("1st-team-image");
            $team_2_img = $product->get_meta("2nd-team-image");

            $team_images = [$team_1_img, $team_2_img];

            //Loop through each team club image (only 2 iterations)
            foreach($team_images as $index => $team_image){

                //Check if file exists on url of team image
                if(!fileExistsOnUrl($team_image)){
                    //If file doesn't exist, extract the filename from the
                    //team image variable. If team image variable is an empty string
                    //set filename to the team's name that is missing the file
                    $filename = basename($team_image);
                    if(!$team_image || $team_image === ""){
                        $filename = teamNamesFromTitle($product->get_title())[$index];
                    }
                    //Check if $missing_teams already has an entry of this team
                    $skip = false;
                    foreach($missing_teams as $missing_team){
                        if($missing_team === $filename){
                            $skip = true;
                        }
                    }
                    if(!$skip){
                        //If $missing_teams doesn't have entry for this team, then add it
                        array_push($missing_teams, $filename);
                    }
                }
            }
        }

        //Return missing stadiums and teams
        return [
            "missing_stadiums" => $missing_stadiums,
            "missing_teams" => $missing_teams
        ];
    }