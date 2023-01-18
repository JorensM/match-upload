<?php

    //Core
    require_once(__DIR__."/../../wp_init.php");

    //Functions
    require_once(__DIR__."/../functions/fileExistsOnUrl.php");
    require_once(__DIR__."/../functions/getMissingProductFiles.php");
    require_once(__DIR__."/../functions/printRPre.php");

    $products = wc_get_products(array("limit" => -1, "ignore_alteration" => true));

    echo count($products) . "\n";

    echo "Locating missing files, this will take a minute or two...\n";

    //echo gettype($products) . "\n";
    //echo get_class($products[0]) . "\n";

    //Find missing files
    $missing_files = getMissingProductFiles($products);

    //printRPre($missing_files);

    $missing_stadiums = $missing_files["missing_stadiums"];
    $missing_teams = $missing_files["missing_teams"];

    //Echo missing stadium files
    echo "<h4>Missing Stadium images</h4>";
    if(count($missing_stadiums) > 0){
        foreach($missing_stadiums as $missing_stadium){
            echo $missing_stadium . "<br>";
        }
    }else{
        echo "No missing files found<br>";
    }

    //Echo missing club files
    echo "<h4>Missing club images</h4>";
    if(count($missing_teams) > 0){
        foreach($missing_teams as $missing_team){
            echo $missing_team . "<br>";
        }
    }else{
        echo "No missing files found<br>";
    }