<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Core
    require_once("wp_init.php");

    //Functions
    require_once("php/functions/getHiddenCategoryIds.php");
    require_once("php/functions/printRPre.php");

    $cat_test =get_terms(array(
        "taxonomy" => "product_cat",
        "meta_key" => "hide",
    ));

    printRPre($cat_test);


