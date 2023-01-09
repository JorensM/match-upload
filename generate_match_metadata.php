<?php  

    require_once("wp_init.php");    
    require_once("product_functions.php");

    function replace_umlauts($str){
        $new_str = $str;
        $new_str = str_replace("ë", "e", $new_str);
        $new_str = str_replace("ö", "o", $new_str);
        $new_str = str_replace("ü", "u", $new_str);

        return $new_str;
    }

    //FC Barcelona = fcbarcelona
    function cleanstr($str) {
        $new_string = $str;
        $new_string = replace_umlauts($new_string);
        $new_string = str_replace("&", "", $new_string);
        $new_string = urlencode(strtolower(str_replace(" ","",preg_replace("/([a-z])[a-z]+;/i", "$1", htmlentities($new_string)))));
        $newer_string = preg_replace('/[^A-Za-z0-9\-]/', '', $new_string);
        return $newer_string;
    }

    //FC Barcelona = FCBarcelona
    function cleanstr2($str){
        $new_string = $str;
        $new_string = replace_umlauts($new_string);
        $new_string = str_replace("&", "", $new_string);
        $new_string = urlencode(str_replace(" ","",preg_replace("/([a-z])[a-z]+;/i", "$1", htmlentities($new_string))));
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

    function png_to_webp_and_upload($png_path){
        error_log("Creating webp from: $png_path");
        $png_img = imagecreatefrompng($png_path);
        if($png_img){
            error_log("png created");
            imagesavealpha($png_img, true);
            $webp_path = str_replace('png', 'webp', $png_path);
            $basename = basename($webp_path);
            $final_path = wp_upload_dir()["path"] . "/" . $basename;
            $webp = imagewebp($png_img, $final_path);
            if($webp){
                error_log("file uploaded to $final_path");
            }else{
                error_log("Failed uploading to $final_path");
            }
            
        }else{
            error_log("png creation failed");
        }
        
    }

    function convert_team_img_to_webp($match, $team){
        $team_1_first = wp_upload_dir()["url"] . "/" . cleanstr($match["home_club"]) . ".png";
        $team_2_first = wp_upload_dir()["url"] . "/" . cleanstr($match["away_club"]) . ".png";

        $team_1_second = wp_upload_dir()["url"] . "/" . cleanstr2($match["home_club"]) . ".png";
        $team_2_second = wp_upload_dir()["url"] . "/" . cleanstr2($match["away_club"]) . ".png";

        error_log("team: $team");
        if($team === 1){
            if(file_exists_on_url($team_1_first)){
                error_log("file exists on url1");
                png_to_webp_and_upload($team_1_first);
            }else if(file_exists_on_url($team_1_second)){
                error_log("file exists on url2");
                png_to_webp_and_upload($team_1_second);
            }
        }
        else if($team === 2){
            if(file_exists_on_url($team_2_first)){
                png_to_webp_and_upload($team_2_first);
            }else if(file_exists_on_url($team_2_second)){
                png_to_webp_and_upload($team_2_second);
            }
        }
        
    }

    function generate_team_img_filenames($match){

        $team_1 = wp_upload_dir()["url"] . "/" . cleanstr($match["home_club"]) . ".webp";
        $team_2 = wp_upload_dir()["url"] . "/" . cleanstr($match["away_club"]) . ".webp";

        $team_1_webp2 = wp_upload_dir()["url"] . "/" . cleanstr2($match["home_club"]) . ".webp";
        $team_2_webp2 = wp_upload_dir()["url"] . "/" . cleanstr2($match["away_club"]) . ".webp";

        // error_log("checking if has webp");
        // if(!file_exists_on_url($team_1) && !file_exists_on_url($team_1_webp2)){
        //     error_log("doens't have webp");
        //     convert_team_img_to_webp($match, 1);
        // }

        // if(!file_exists_on_url($team_2) && !file_exists_on_url($team_2_webp2)){
        //     convert_team_img_to_webp($match, 2);
        // }

        if(!file_exists_on_url($team_1)){
            $team_1 = wp_upload_dir()["url"] . "/" . cleanstr2($match["home_club"]) . ".webp";
            if(!file_exists_on_url($team_1)){
                error_log("Warning: the following club is missing .webp format image - ". $match['home_club']);
                $team_1 = wp_upload_dir()["url"] . "/" . cleanstr($match["home_club"]) . ".png";
                if(!file_exists_on_url($team_1)){
                    $team_1 = wp_upload_dir()["url"] . "/" . cleanstr2($match["home_club"]) . ".png";
                }
            }
        }
        if(!file_exists_on_url($team_2)){
            $team_2 = wp_upload_dir()["url"] . "/" . cleanstr2($match["away_club"]) . ".webp";
            if(!file_exists_on_url($team_2)){
                error_log("Warning: the following club is missing .webp format image - ". $match['away_club']);
                $team_2 = wp_upload_dir()["url"] . "/" . cleanstr($match["away_club"]) . ".png";
                if(!file_exists_on_url($team_2)){
                    $team_2 = wp_upload_dir()["url"] . "/" . cleanstr2($match["away_club"]) . ".png";
                }
            }
        }
        // if(!file_exists_on_url($team_2)){
        //     $team_2 = wp_upload_dir()["url"] . "/" . cleanstr2($match["away_club"]) . ".png";
        // }

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
        $match_date_confirm = $match["match_fixed"];//($match["match_date"] === null || $match["match_date"] === "") ? "false" : "true";
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