<?php
    /**
     * Plugin Name: Match Upload
     * Description: Custom plugin made for matchticketshop.com to bulk upload matches from a .CSV
     * Plugin Author: JorensM
     * Developer: JorensM
     */

//Requires
require_once("const.php");
require_once("update_product_visibility_fn.php");
require_once("get_hidden_category_ids.php");

//Init settings
function match_upload_settings_init(){

}

//Init settings page
function match_upload_settings_page(){
    add_menu_page("Match Upload", "Match Upload", "manage_options", "match_upload_page", "match_upload_page_html");
    add_submenu_page("match_upload_page", "Logs", "Logs", "manage_options", "match_upload_logs", "match_upload_logs_html");
    add_submenu_page("match_upload_page", "Options", "Options", "manage_options", "match_upload_options", "match_upload_options_html");
}

add_action("admin_menu", "match_upload_settings_page");

//Settings page HTML
function match_upload_page_html(){
    global $UPLOAD_MATCHES_ACTION_URL;
    global $LOAD_PROGRESS_URL;
    global $CANCEL_URL;
    global $LOGS_URL;

    echo 
        "<form id='match-upload-form' class='upload-form' action='" . $UPLOAD_MATCHES_ACTION_URL . "' method='post' enctype='multipart/form-data'>
            <label for='matches-file'>Upload matches file</label>
            <input id='match-upload-file' type='file' name='matches-file' required>
            <br>
            <div style='display: flex;align-items: center;'>
                <input type='checkbox' id='write-logs' name='write-logs' checked>
                <label for='write-logs'>Write logs</label>
            </div>
            <br>
            <button type='button' onclick='upload_matches()'>Upload</button>
        </form>
        <button id='cancel-button' type='button' onclick='cancel_upload()' style='display: none'>Cancel</button>
        <span id='match-upload-error' class='match-upload-error'></span>
        <div class='match-upload-progress' id='match-upload-progress'>
            <span id='progress-index'></span><br>
            <span id='progress-title'></span><br>
            <span id='progress-status'></span><br>
            <span>Time elapsed: <span id='progress-time'></span> seconds</span><br>
        </div>
        <div id='match-upload-end' style='flex-direction: column'>
            <span id='progress-end'></span><br>
            <span>Time to complete: <span id='progress-end-time'></span> seconds<span><br>
        </div>
        <script>
            let error_element = document.getElementById('match-upload-error');

            let progress_end_element = document.getElementById('progress-end');
            matchUploadForm = document.getElementById('match-upload-form');
            progressDiv = document.getElementById('match-upload-progress');
            progressDiv.style.display = 'none';
            
            let interval;
            window.onload = () => {
                interval = setInterval(get_progress, 1000);

                clear_progress();
            }

            function cancel_upload(){
                var request = new Request('" . $CANCEL_URL . "',
                    {
                        method: 'POST',
                        credentials: 'same-origin'
                    }
                );

                fetch(request)
                .catch(err => {
                    console.log('cancel error');
                    console.log(err);   
                })
            }

            function upload_matches(){
                error_element.innerHTML = '';
                let file = document.getElementById('match-upload-file').files[0];
                const write_logs = document.getElementById('write-logs').checked;

                if(file === undefined){
                    error_element.innerHTML = 'Please select a file';
                    return;
                }

                const formData = new FormData();
                formData.append('matches-file', file);
                console.log('write_logs: ');
                console.log(write_logs);
                formData.append('write-logs', write_logs);

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
                
                
                //let interval = setInterval(get_progress, 1000);
                //console.log('interval: ');
                //console.log(interval);
                
                //clear_progress();

                fetch(request)
                .then(response => {return response.json()})
                .then(data => {
                    console.log(data);
                    if(data.error !== ''){
                        progress_end_element.innerHTML = 'an error occured: <br>' + data.error;
                        clearInterval(interval);
                    }
                    if(data.success){
                        progress_end_element.innerHTML = 'upload complete!';
                    }
                    //clearInterval(interval);
                    //clear_progress();
                    //progress_end_element.innerHTML = 'sucessfully added products, you may now leave this page';
                })
                .catch(err => {
                    //console.log('ABCD');
                    console.log(err);
                    clearInterval(interval);

                    progress_end_element.innerHTML = 'an error occured: ' + err;
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
                    if(data.error){
                        progress_end_element.innerHTML = 'an error occured: <br>' + data.error_message;
                        clearInterval(interval);
                    }else{
                        render_progress(data.index, data.title, data.new, data.started, data.finished, data.start_time, data.end_time);
                    }
                })
                .catch(err => {
                    console.log('err');
                    console.log(err);
                });
            }

            function render_progress(index, title, status, started, finished, start_time, end_time){
                indexElement = document.getElementById('progress-index');
                titleElement = document.getElementById('progress-title');
                statusElement = document.getElementById('progress-status');
                cancelButton = document.getElementById('cancel-button');
                progressTimeElement = document.getElementById('progress-time');
                progressEndDiv = document.getElementById('match-upload-end');
                

                progressEndDiv.style.display = 'none';

                console.log(start_time);
                console.log(end_time);

                progressEndTimeElement = document.getElementById('progress-end-time');

                if(finished){
                    progressEndDiv.style.display = 'flex';
                    document.getElementById('progress-end').innerHTML = 'Upload complete!';
                    cancelButton.style.display = 'none';
                    matchUploadForm.style.display = 'flex';
                    progressEndTimeElement.innerHTML = end_time - start_time;
                    progressDiv.style.display = 'none';
                }
                if(started){
                    progressDiv.style.display = 'flex';
                    cancelButton.style.display = 'block';
                    matchUploadForm.style.display = 'none';
                    document.getElementById('progress-index').innerHTML = index;
                    document.getElementById('progress-title').innerHTML = title;
                    if(status === true){
                        document.getElementById('progress-status').innerHTML = 'Product doesn\'t exist, adding';
                    }else{
                        document.getElementById('progress-status').innerHTML = 'Product already exists, updating';
                    }

                    progressTimeElement.innerHTML = end_time - start_time;
                }else{
                    cancelButton.style.display = 'none';
                    indexElement.innerHTML = '';
                    titleElement.innerHTML = '';
                    statusElement.innerHTML = '';
                    matchUploadForm.style.display = 'flex';
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

function match_upload_logs_html(){
    global $LOGS_URL;

    echo "
        <pre>
            <div id='match-upload-logs' class='match-upload-logs'>
                logs
            </div>
        </pre>
        
        <script>
            let logs_string = '';
            console.log('bca');
            window.onload = () => {
                console.log('abc');
                setInterval(() => {
                    update_logs();
                }, 1000);
            }

            function update_logs(){
                console.log('updating logs: ');
                const request = new Request('" . $LOGS_URL . "',
                    {
                        method: 'POST',
                        credentials: 'same-origin'
                    }
                );

                fetch(request)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    render_logs(data.logs);
                })
                .catch(err => {
                    console.log('logs error');
                    console.log(err);
                });
            }

            function render_logs(logs){
                document.getElementById('match-upload-logs').innerHTML = logs;
            }
        </script>
    ";
}

function match_upload_options_html(){
    global $MISSING_FILES_URL;
    global $MISSING_DATA_URL;
    global $APPLY_SEO_URL;
    global $UPDATE_PRODUCT_VISIBILITY_URL;
    global $SEARCH_DB_URL;
    global $SEARCH_SOURCE_URL;

    echo "
        <h1>Options</h1>
        <a href='" . $MISSING_FILES_URL . "'>View missing files</a>
        <br>
        <a href='" . $MISSING_DATA_URL ."'>View missing data</a>
        <br>
        <br>
        <form action='" . $SEARCH_DB_URL . "' method='POST'>
            <input type='text' placeholder='Enter search value' name='search_value'>
            <button type='submit'>Search DB</button>
        </form>
        <br>
        <form action='" . $SEARCH_SOURCE_URL . "' method='POST'>
            <input type='text' placeholder='Enter search value' name='search_value'>
            <button type='submit'>Search website source</button>
        </form>

    ";
}

//Enqueue styles
function match_upload_init_styles(){
    wp_enqueue_style("match-upload-style", plugin_dir_url(__FILE__) . "/style.css");
}
add_action("admin_enqueue_scripts", "match_upload_init_styles");

//Apply meta descriptions to products
function mts_apply_metadesc($desc){
    $post = get_post();
    //$category = get_the_category();
    // echo "id: " . get_the_ID();
    // echo "is_category: " . var_dump(is_product_category());
    // echo "<pre>";
    // print_r($post);
    // echo "</pre>";
    // echo "<pre>";
    // print_r($category);
    // echo "</pre>";
    // $object = get_queried_object();
    // echo "<pre>";
    // print_r($object);
    // echo "</pre>";
    if(is_product_category()){
        $category = get_queried_object();
        if($category->parent > 0){
            $name = $category->name;
            return "Buy $name tickets at Matchticketshop.com ✓ Official tickets and packages ✓ Guaranteed seating together ✓ Safe payment";
        }
        if($category-> parent === 0){
            $name = $category->name;
            return "Buy $name at Matchticketshop.com ✓ Official tickets and packages ✓ Guaranteed seating together ✓ Safe payment";
        }
    }
    else if($post->post_type === "product"){
        //echo "is product";
        //echo $post->post_content;
        return $post->post_content;
        //return "second desc";
    }
}

//Apply meta title to category pages
function mts_apply_metatitle($title){
    if(is_product_category()){
        $category = get_queried_object();
        if($category->parent > 0){
            $name = $category->name;
            return "Buy $name tickets 2022/23 - Matchticketshop.com";
        }
    }
}
add_filter( 'wpseo_title', 'mts_apply_metatitle' );

add_filter("wpseo_metadesc", "mts_apply_metadesc");

//Apply metadata to page
function mts_apply_head(){
    //global $product;

    //Product page metadata
    if(isset($product)){
        //$description = $product->get_description();
        //echo "<meta name='description' content='" . $description . "'>";
    }
    //echo "<meta "
}

add_action("wp_head", "mts_apply_head");

// function update_product_visibility($category_id){

// }

// On category update

function mts_after_category_update($term_id, $tt_id){
    //update_product_visiblity($term_id);
    //error_log("term_id: $term_id, tt_id: $tt_id");
}

add_action("edited_product_cat", "mts_after_category_update", 10, 2);

//Alter query to exclude posts that are outdated, or whose category is hidden
function mts_alter_query($query){
    
    
    // if(is_array($query->get("post_type")) && in_array("product", $query->get("post_type"))){
    //     echo "<pre>";
    //     print_r($query);
    //     echo "</pre>";
    // }

    $is_product = $query->get("post_type") === "product" || (is_array($query->get("post_type")) && in_array("product", $query->get("post_type")));


    // if($is_product){
    //     echo "<pre>";
    //     print_r($query);
    //     echo "</pre>";
    // }
    //echo gettype(is_admin());
    // echo is_admin() ? 'true' : 'false';
    // if(is_admin()){
    //     error_log(print_r($query, true));
    // }

    $is_ajax_search = $query->get("jet_ajax_search") == "1" ? true : false;

    //error_log("hello");
    //error_log("|" . $query->get("jet_ajax_search") . "|");
    //error_log(gettype($query->get("jet_ajax_search")));

    $value = $is_ajax_search ? "true" : "false";

    //error_log("val: " . $value);

    // error_log(print_r($query, true));

    // echo "hello";
    if(($is_product && !is_admin() && !$query->is_singular()) || ($query->is_search() && !is_admin()) || $is_ajax_search){

        //echo "is search";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        //$query->set("s", "chelsea");
        //Hide outdated posts
        //$query->set("meta_key", "match-date");
        //$query->set("meta_value", date("Y-m-d"));
        //$query->set("meta_compare", ">");

        $original_meta_query = $query->get("meta_query");

        $new_meta_query = array(
            "relation" => "AND",
            array(
                "key" => "match-date",
                "value" => date("Y-m-d"),
                "compare" => ">"
            )//,
            //$original_meta_query
        );

        $query->set("meta_query", $new_meta_query);

        //Hide posts that belong to a hidden category

        $hidden_cat_ids = get_hidden_category_ids();

        $original_tax_query = $query->get( 'tax_query', [] );

        $new_tax_query = array(
            array(
                "taxonomy" => "product_cat",
                "field" => "term_id",
                "terms" => $hidden_cat_ids,
                "operator" => "NOT IN"
            ),
            $original_tax_query
        );

        $query->set("tax_query", $new_tax_query);

        $query->set("jet_ajax_search", 0);

        //Second method

        //$query["query"]["tax_query"] = $new_tax_query;
        //$query["query"]["meta_query"] = $new_meta_query;
        //$query->query["tax_query"] = $new_tax_query;
        //$query->query["meta_query"] = $new_meta_query;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

    }
}
add_action( 'pre_get_posts', 'mts_alter_query', 999);

add_action( 'elementor/query/{$query_id}', 'mts_alter_query' );

//Set elementor fonts-display to 'swap'
add_filter( 'elementor_pro/custom_fonts/font_display', function( $current_value, $font_family, $data ) {
	return 'swap';
}, 10, 3 );




