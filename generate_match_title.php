<?php

    function generate_match_title($match){
        return "Tickets " . $match["home_club"] . " vs. " . $match["away_club"];
    }