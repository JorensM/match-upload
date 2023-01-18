<?php

    /**
     * Extract team names from product title
     * 
     * @param string $title title
     * 
     * @return string[] [team_1_name, team_2_name]
     */
    function teamNamesFromTitle(string $title){
        $altered = str_replace("Tickets ", "", $title);
        $team_names = explode(" vs. ", $altered);

        return $team_names;
    }