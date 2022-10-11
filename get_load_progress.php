<?php

    session_start();

    ini_set('max_execution_time', 0);

    $progress_data = $_SESSION["progress_data"];

    ob_flush(); 
    flush(); 

    echo json_encode($progress_data);