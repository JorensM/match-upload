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
    global $LOAD_PROGRESS_URL;

    echo 
        "<form id='match-upload-form' class='upload-form' action='" . $UPLOAD_MATCHES_ACTION_URL . "' method='post' enctype='multipart/form-data'>
            <label for='matches-file'>Upload matches file</label>
            <input id='match-upload-file' type='file' name='matches-file' required>
            <button type='button' onclick='upload_matches()'>Upload</button>
        </form>
        <span id='match-upload-error' class='match-upload-error'></span>
        <div class='match-upload-progress'>
            <span id='progress-index'></span><br>
            <span id='progress-title'></span><br>
            <span id='progress-status'></span><br>
            <span id='progress-end'></span><br>
        </div>
        <script>
            let error_element = document.getElementById('match-upload-error');

            let progress_end_element = document.getElementById('progress-end');

            function upload_matches(){
                error_element.innerHTML = '';
                let file = document.getElementById('match-upload-file').files[0];

                if(file === undefined){
                    error_element.innerHTML = 'Please select a file';
                    return;
                }

                const formData = new FormData();
                formData.append('matches-file', file);

                var request = new Request('" . $UPLOAD_MATCHES_ACTION_URL . "',
                    {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                        // headers: {
                        //     'Content-Type': 'multipart/form-data'
                        // }
                    }
                )
                
                
                let interval = setInterval(get_progress, 1000);
                console.log('interval: ');
                console.log(interval);
                
                clear_progress();

                fetch(request)
                .then(response => {return response.json()})
                .then(data => {
                    console.log(data);
                    clearInterval(interval);
                    clear_progress();
                    progress_end_element.innerHTML = 'sucessfully added products, you may now leave this page';
                })
                .catch(err => {
                    console.log(err);
                    clearInterval(interval);

                    progress_end_element.innerHTML = 'an error occured';
                });
            }

            function get_progress(){
                var request = new Request('" . $LOAD_PROGRESS_URL. "',
                    {
                        method: 'POST',
                        credentials: 'same-origin'
                    }
                )

                fetch(request)
                .then(response => {return response.json()})
                .then(data => {
                    console.log('progress data: ');
                    console.log(data);
                    render_progress(data.index, data.title, data.new);
                })
                .catch(err => {
                    console.log('err');
                    console.log(err);
                });
            }

            function render_progress(index, title, status){
                document.getElementById('progress-index').innerHTML = index;
                document.getElementById('progress-title').innerHTML = title;
                if(status === true){
                    document.getElementById('progress-status').innerHTML = 'Product doesn\'t exist, adding';
                }else{
                    document.getElementById('progress-status').innerHTML = 'Product already exists, updating';
                }
            }

            function clear_progress(){
                document.getElementById('progress-index').innerHTML = '';
                document.getElementById('progress-title').innerHTML = '';
                document.getElementById('progress-status').innerHTML = '';
                progress_end_element.innerHTML = '';
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


