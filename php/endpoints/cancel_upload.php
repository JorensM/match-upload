<?php
    session_start();
    $_SESSION["cancel_upload"] = true;

    $start_time = 0;
    if(isset($_SESSION["progress_data"]["start_time"])){
        $start_time = $_SESSION["progress_data"]["start_time"];
    }
    
    $_SESSION["progress_data"] = [
        "index" => 0,
        "title" => "",
        "new" => false,
        "started" => false,
        "finished" => true,
        "start_time" => $start_time,
        "end_time" => time()
    ];
    session_write_close();