<?php

    require_once(__DIR__."/../wp_init.php");
    require_once("abstract/AbstractData.php");
    require_once(__DIR__."/../functions/getImageIdOfStadium.php");

    class MatchObject extends AbstractData {
        
        public function generateMatchTitle(){
            $home_club = $this->get("home_club");
            $away_club = $this->get("away_club");

            return "Tickets " . $home_club . " vs. " . $away_club;
        }

        public function generateDescription(){
            $home_club = $this->get("home_club");
            $away_club = $this->get("away_club");

            return "Buy tickets for the football match " .  $home_club . " vs. " . $away_club . " and enjoy this exciting game!";
        }

        // public function generateMetaInfo(){
        //     $this->data["title"] = $this->generateMatchTitle();
        //     $this->data["description"] = $this->generateDescription();
        //     $this->data["categories"] = $this->generateCategoryIds();
        //     $this->data["image_id"] = $this->generateStadiumImageId();
        // }

        /**
         * Returns array of ids for categories to which this match should belong
         */
        public function generateCategoryIds(){
            $home_club = $this->get("home_club");
            $tournament = $this->get("tournament");

            $term = get_term_by('name', $home_club, 'product_cat');
            $term2 = get_term_by('name', $tournament, 'product_cat');

            $category_ids = [];

            if($term){
                array_push($category_ids, $term->term_id);
            }

            array_push($category_ids, $term2->term_id);

            //$category_ids = [$term->term_id, $term2->term_id];

            return $category_ids;
        }

        public function generateStadiumImageId(){
            $stadium_name = $this->get("stadium");

            return getImageIdOfStadium($stadium_name);
        }

        public function generateTeamImageFilenames(){
            $home_club = $this->get("home_club");
            $away_club = $this->get("away_club");

            $team_1 = wp_upload_dir()["url"] . "/" . cleanstr($home_club) . ".webp";
            $team_2 = wp_upload_dir()["url"] . "/" . cleanstr($away_club) . ".webp";

            if(!file_exists_on_url($team_1)){
                $team_1 = wp_upload_dir()["url"] . "/" . cleanstr2($home_club) . ".webp";
                if(!file_exists_on_url($team_1)){
                    error_log("Warning: the following club is missing .webp format image - ". $home_club);
                    $team_1 = wp_upload_dir()["url"] . "/" . cleanstr($home_club) . ".png";
                    if(!file_exists_on_url($team_1)){
                        $team_1 = wp_upload_dir()["url"] . "/" . cleanstr2($home_club) . ".png";
                    }   
                }
            }

            if(!file_exists_on_url($team_2)){
                $team_2 = wp_upload_dir()["url"] . "/" . cleanstr2($away_club) . ".webp";
                if(!file_exists_on_url($team_2)){
                    error_log("Warning: the following club is missing .webp format image - ". $away_club);
                    $team_2 = wp_upload_dir()["url"] . "/" . cleanstr($away_club) . ".png";
                    if(!file_exists_on_url($team_2)){
                        $team_2 = wp_upload_dir()["url"] . "/" . cleanstr2($away_club) . ".png";
                    }
                }
            }

            return array(
                $team_1,
                $team_2
            );
        }

        public function generateVariationData(){
            $variation_data = [];
            for($i = 1; $i < 5; $i++){
                $price = $this->get("cat_" . $i . "_price");
                $qty = $this->get("cat_" . $i . "_qty");

                //Whether the variation should be enabled
                //If price or qty is 0/unspecified, variation will be disabled
                $variation_enable = !($price === "0" || $price === null || $price === "" || $price === 0) && !($qty === "0" || $qty === null || $qty === "" || $qty === 0);


                $variation = [
                    "enable" => $variation_enable,
                    "description" => "Category " . $i,
                    "name" => $this->generateMatchTitle() . " - Category " . $i,
                    "regular_price" => $price,
                    "qty" => $qty,
                    
                ];
                array_push($variation_data, $variation); 

            }
            return $variation_data;
        }

    }