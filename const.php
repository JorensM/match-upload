<?php

    require_once("wp_init.php");

    $UPLOAD_MATCHES_ACTION_URL = plugin_dir_url(__FILE__) . "upload_matches.php";
    $LOAD_PROGRESS_URL = plugin_dir_url(__FILE__) . "get_load_progress.php";
    $CANCEL_URL = plugin_dir_url(__FILE__) . "cancel_upload.php";
    $LOGS_URL = plugin_dir_url(__FILE__) . "get_logs.php";
    $MISSING_FILES_URL = plugin_dir_url(__FILE__) . "view_missing_files.php";
    $MISSING_DATA_URL = plugin_dir_url(__FILE__) . "view_missing_data.php";
    $APPLY_SEO_URL = plugin_dir_url(__FILE__) . "apply_seo.php";
    $UPDATE_PRODUCT_VISIBILITY_URL = plugin_dir_url(__FILE__) . "update_product_visibility.php";
    $SEARCH_DB_URL = plugin_dir_url(__FILE__) . "search_db.php";
    $SEARCH_SOURCE_URL = plugin_dir_url(__FILE__) . "search_source.php";
    $TEST_URL = plugin_dir_url(__FILE__) . "test.php";
    $CHECK_CLUB_ICONS_URL = plugin_dir_url(__FILE__) . "check_club_icons.php";
    //$UPDATE_VISIBILITY_FN_URL = plugin_dir_url(__FILE__) . "update_product_visibility_fn.php";

    //WooCommerce API keys (read/write)
    $WOO_API_USER = "ck_0a3df38292cd32c486ce2a1ddda2f950890f8ed5";
    $WOO_API_PASS = "cs_de6dbb7da6ed66a50de75b56c686044fc9193c7f";

    $MANAGE_STOCK = false;