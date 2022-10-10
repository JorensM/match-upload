<?php

    require_once("get_image_id_of_stadium.php");

    //Set stadium image for product
    function set_stadium_image($product, $match){
        //Get image id of stadium
        $stadium_image_id = get_image_id_of_stadium($match["stadium"]);

        echo "Getting stadium image id: " . $stadium_image_id;

        $product->set_image_id($stadium_image_id);
    }