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

    }