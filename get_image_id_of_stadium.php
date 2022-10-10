<?php

    require_once("wp_init.php");

    function get_image_id_of_stadium($stadium_name){
        $final_str = $stadium_name;
        $final_str = strtolower($stadium_name);
        $final_str = str_replace(" ", "", $stadium_name);

        $image_id = attachment_url_to_postid(wp_upload_dir() . $final_str . ".svg");

        return $image_id;
    }