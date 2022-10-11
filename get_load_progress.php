<?php

    session_start();
    $progress_data = $_SESSION["progress_data"];
    echo json_encode($progress_data);