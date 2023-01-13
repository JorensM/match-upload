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

        public function generateMetaInfo(){
            $this->data["title"] = $this->generateMatchTitle();
            $this->data["description"] = $this->generateDescription();
            $this->data["categories"] = $this->generateCategoryIds();
            $this->data["image_id"] = $this->generateStadiumImageId();
        }

        /**
         * Returns array of ids for categories to which this match should belong
         */
        public function generateCategoryIds(){
            $home_club = $this->get("home_club");
            $tournament = $this->get("tournament");

            $term = get_term_by('name', $home_club, 'product_cat');
            $term2 = get_term_by('name', $tournament, 'product_cat');

            $category_ids = [$term->term_id, $term2->term_id];

            return $category_ids;
        }

        public function generateStadiumImageId(){
            $stadium_name = $this->get("stadium");

            return getImageIdOfStadium($stadium_name);
        }

        public function generateVariationData(){
            $variation_data = [];
            for($i = 1; $i < 5; $i++){
                $price = $this->get("cat_" . $i . "_price");
                $qty = $this->get("cat_" . $i . "_qty");

                echo "price: " . $price;
                echo "qty: " . $qty;

                //Whether the variation should be enabled
                //If price or qty is 0/unspecified, variation will be disabled
                $variation_enable = !($price === "0" || $price === null || $price === "" || $price === 0) && !($qty === "0" || $qty === null || $qty === "" || $qty === 0);

                echo PHP_EOL . "enable: " . var_dump($variation_enable);

                $variation = [
                    "enable" => $variation_enable,
                    "number" => $i,
                    "name" => $this->generateMatchTitle() . " - Category " . $i,
                    "regular_price" => $price,
                    "qty" => $qty,
                    
                ];
                array_push($variation_data, $variation); 
                //}

            }
            return $variation_data;
        }

    }