<?php

    require_once("wp_init.php");
    // require_once("util.php");

    function get_image_id_of_stadium($stadium_name){
        $final_str = mb_strtolower($stadium_name);
        $final_str = str_replace(" ", "", $final_str);
        $final_str = str_replace("'", "", $final_str);

        $upload_dir = wp_upload_dir()["url"] . "/";// . "/2022/07/";

        $final_url = $upload_dir . $final_str . ".svg";
        if(!file_exists_on_url($final_url)){
            $final_url = $upload_dir . $final_str . ".png";
        }

        $image_id = attachment_url_to_postid($final_url);

        return $image_id;
    }