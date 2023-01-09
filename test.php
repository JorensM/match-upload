<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Classes
    require_once("./classes/DataEntry.php");
    require_once("./classes/DataEntryChecker.php");
    require_once("./classes/DataEntryParams.php");

    //Functions
    require_once("./functions/echoNl.php");

    //$params = new DataEntryParams();

    $entry = new DataEntry(
        new DataEntryParams([
            "required" => true,
            "value" => "hello",
            "type" => "string"
        ]),
        new DataEntryChecker(),
    );

    $val = $entry->getValue();

    echoNl($val);
    

    //print_r($entry->setValue("hello"));