<?php  

    require_once("wp_init.php");    
    require_once("product_functions.php");

    function cleanstr($str) {
        return urlencode(strtolower(str_replace(" ","",preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($str)))));
    }

    function generate_team_img_filenames($match){

        $team_1 = wp_upload_dir()["url"] . "/" . cleanstr($match["home_club"]) . ".png";
        $team_2 = wp_upload_dir()["url"] . "/" . cleanstr($match["away_club"]) . ".png";

        return array(
            $team_1,
            $team_2
        );
    }

    function generate_match_metadata($match){

        $match_date_obj = strtotime($match["match_date"]);

        $match_date = date("Y-m-d", $match_date_obj);
        $match_description = generate_product_description($match);
        $match_tournament = $match["tournament"];
        $match_date_confirm = ($match["match_date"] === null || $match["match_date"] === "") ? "false" : "true";
        $team_img_urls = generate_team_img_filenames($match);
        $team_1_img_url = $team_img_urls[0];
        $team_2_img_url = $team_img_urls[1];

        return array(
            "match-description" => $match_description,
            "match-date" => $match_date,
            "match-tournament" => $match_tournament,
            "match-date-confirm" => $match_date_confirm,
            "team1-img" => $team_1_img_url,
            "team2-img" => $team_2_img_url
        );
    }