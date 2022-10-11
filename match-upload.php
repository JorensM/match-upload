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
        "<form id='match-upload-form' class='upload-form' action='" . $UPLOAD_MATCHES_ACTION_URL . "' method='post' enctype='multipart/form-data'>
            <label for='matches-file'>Upload matches file</label>
            <input id='match-upload-file' type='file' name='matches-file' required>
            <button type='button' onclick='upload_matches()'>Upload</button>
        </form>
        <span id='match-upload-error' class='match-upload-error'></span>
        <div class='match-upload-progress'>
            40/300
            Tickets xxx
            Updating...
        </div>
        <script>
            let error_element = document.getElementById('match-upload-error');

            function upload_matches(){
                error_element.innerHTML = '';
                let file = document.getElementById('match-upload-file').files[0];
                console.log('file: ');
                console.log(file);

                

                console.log(formData);

                if(file === undefined){
                    error_element.innerHTML = 'Please select a file';
                    return;
                }

                formData = new FormData();
                formData.append('matches-file', file);

                console.log(formData);

                var request = new Request('" . $UPLOAD_MATCHES_ACTION_URL . "',
                    {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )

                fetch(request)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                })
                .catch(err => {
                    console.log(err);
                });
            }
        </script>
        ";

    // echo
    //     "<form action='" . plugin_dir_url(__FILE__) . "test_meta_box.php" . "' method='post'>
    //         <input type='submit'>Test Meta Box</input>
    //     </form>";
}

//Enqueue styles
function match_upload_init_styles(){
    wp_enqueue_style("match-upload-style", plugin_dir_url(__FILE__) . "/style.css");
}
add_action("admin_enqueue_scripts", "match_upload_init_styles");


