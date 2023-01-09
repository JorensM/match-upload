<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Classes
    require_once("./classes/DataEntry.php");
    require_once("./classes/DataEntryChecker.php");
    require_once("./classes/DataEntryParams.php");
    require_once("./classes/SessionDataManager.php");
    require_once("./classes/SessionData.php");
    require_once("./classes/SessionDataEntry.php");

    //Functions
    require_once("./functions/echoNl.php");

    //$params = new DataEntryParams();

    $entry = new SessionDataEntry(
        new DataEntryParams([
            "required" => false,
            "value" => "",
            "type" => ""
        ]),
        new DataEntryChecker(),
    );

    $session = new SessionDataManager(
        new SessionData($entry)
    );

    $first_set = "first_set";

    $session->setValues(
        $first_set,
        [
            "first_key" => "hello",
            "second_key" => "hello!"
        ],
    );

    $vals = $session->getValues($first_set);

    print_r($vals);
    

    //print_r($entry->setValue("hello"));