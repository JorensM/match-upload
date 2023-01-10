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
    require_once("./functions/printRPre.php");

    session_start();

    $session = new SessionDataManager($_SESSION);

    session_write_close();

    //printRPre($_SESSION);


    session_start();
    printRPre($_SESSION);
    session_write_close();

    $session->set("hello", "yo123hhbbbbh");

    $session->setEntries("test",
        [
            "first" => "ayy123",
            "second" => "hey123"
        ]
    );

    session_start();
    printRPre($_SESSION);
    session_write_close();

    $output = $session->getEntries("test", ["first", "second"]);

    printRPre($output);
