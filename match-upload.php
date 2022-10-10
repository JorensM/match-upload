<?php
    /**
     * Plugin Name: Match Upload
     * Description: Custom plugin made for matchticketshop.com to bulk upload matches from a .CSV
     * Plugin Author: JorensM
     * Developer: JorensM
     */

//Requires
require_once("const.php");

//Init settings
function match_upload_settings_init(){

}

//Init settings page
function match_upload_settings_page(){
    add_menu_page("Match Upload", "Match Upload", "manage_options", "match_upload_page", "match_upload_page_html");
}

add_action("admin_menu", "match_upload_settings_page");

//Settings page HTML
function match_upload_page_html(){
    global $UPLOAD_MATCHES_ACTION_URL;

    echo 
        "<form class='upload-form' action='" . $UPLOAD_MATCHES_ACTION_URL . "' method='post' enctype='multipart/form-data'>
            <label for='matches-file'>Upload matches file</label>
            <input type='file' name='matches-file' required>
            <input type='submit'>Upload</input>
        </form>";

    echo
        "<form action='" . plugin_dir_url(__FILE__) . "test_meta_box2.php" . "' method='post'>
            <input type='submit'>Test Meta Box</input>
        </form>";
}

//Enqueue styles
function match_upload_init_styles(){
    wp_enqueue_style("match-upload-style", plugin_dir_url(__FILE__) . "/style.css");
}
add_action("admin_enqueue_scripts", "match_upload_init_styles");


