<?php

    $output = file_get_contents("logs.txt");

    echo json_encode(["logs" => $output]);