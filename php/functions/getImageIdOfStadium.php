<?php

    require_once(__DIR__."/../../wp_init.php");
    require_once("fileExistsOnUrl.php");
    // require_once("util.php");

    function getImageIdOfStadium($stadium_name){
        $final_str = mb_strtolower($stadium_name);
        $final_str = str_replace(" ", "", $final_str);
        $final_str = str_replace("'", "", $final_str);

        $upload_dir = wp_upload_dir()["url"] . "/";// . "/2022/07/";

        $final_url = $upload_dir . $final_str . ".svg";
        if(!fileExistsOnUrl($final_url)){
            $final_url = $upload_dir . $final_str . ".png";
        }

        $image_id = attachment_url_to_postid($final_url);

        return $image_id;
    }