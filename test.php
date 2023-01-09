<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("./classes/DataEntry.php");

    $entry = new DataEntry(
        [
            "required" => true,
            "value" => "hello",
            "type" => "string"
        ],
        new DataEntryChecker()
    );

    print_r($entry->setValue("hello"));