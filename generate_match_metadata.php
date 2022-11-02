<?php  

    require_once("wp_init.php");    
    require_once("product_functions.php");

    //FC Barcelona = fcbarcelona
    function cleanstr($str) {
        $new_string = urlencode(strtolower(str_replace(" ","",preg_replace("/([a-z])[a-z]+;/i", "$1", htmlentities($str)))));
        $newer_string = preg_replace('/[^A-Za-z0-9\-]/', '', $new_string);
        return $newer_string;
    }

    //FC Barcelona = FCBarcelona
    function cleanstr2($str){
        $new_string = urlencode(str_replace(" ","",preg_replace("/([a-z])[a-z]+;/i", "$1", htmlentities($str))));
        $newer_string = preg_replace('/[^A-Za-z0-9\-]/', '', $new_string);
        return $newer_string;
    }

    function file_exists_on_url($url){
        $file = $url;
        $file_headers = @get_headers($file);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        else {
            return true;
        } 
    }

    function generate_team_img_filenames($match){

        $team_1 = wp_upload_dir()["url"] . "/" . cleanstr($match["home_club"]) . ".png";
        $team_2 = wp_upload_dir()["url"] . "/" . cleanstr($match["away_club"]) . ".png";

        if(!file_exists_on_url($team_1)){
            $team_1 = wp_upload_dir()["url"] . "/" . cleanstr2($match["home_club"]) . ".png";
        }
        if(!file_exists_on_url($team_2)){
            $team_2 = wp_upload_dir()["url"] . "/" . cleanstr2($match["away_club"]) . ".png";
        }

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
        $location = $match["stadium"];
        $time = $match["match_time"];

        return array(
            "match-description" => $match_description,
            "match-date" => $match_date,
            "match-tournament" => $match_tournament,
            "match-date-confirm" => $match_date_confirm,
            "match-location" => $location,
            "match-time" => $time,
            "team1-img" => $team_1_img_url,
            "team2-img" => $team_2_img_url
        );
    }