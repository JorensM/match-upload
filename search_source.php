<?php

    $search_value = $_POST["search_value"];


    $urls_to_search = [
        "https://www.matchticketshop.com/",
        "https://www.matchticketshop.com/footballtickets/",
        "https://www.matchticketshop.com/footballtravel/",
        "https://www.matchticketshop.com/reviews/",
        "https://www.matchticketshop.com/about-us/",
        "https://www.matchticketshop.com/contact/",
        "https://www.matchticketshop.com/competitions/tickets-england/",
        "https://www.matchticketshop.com/competitions/tickets-england/arsenal/",
        "https://www.matchticketshop.com/match/tickets-arsenal-vs-afc-bournemouth/"
    ];

    $results = [];

    foreach($urls_to_search as $url){
        $source_code = file_get_contents($url);

        if(str_contains($source_code, $search_value)){
            array_push($results, $url);
        }
    }

    if(count($results) > 0){
        echo "Found '$search_value' in: <br>";
        foreach($results as $result){
            echo $result . "<br>";
        }
    }else{
        echo "Could not find '$search_value'";
    }