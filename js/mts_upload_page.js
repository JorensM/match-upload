// match_upload_form = null;
// cancel_button = null;
// progress_div = null;
// error_element = null;

let match_upload_form = document.getElementById("match-upload-form");
let match_upload_form_submit_button = document.getElementById("match-upload-submit");
let cancel_button = document.getElementById("cancel-button");
let progress_div = document.getElementById("match-upload-progress");
let error_element = document.getElementById("match-upload-error");
let success_element = document.getElementById("match-upload-success");

// function defineElements(){
//     match_upload_form = document.getElementById("match-upload-form");
//     cancel_button = document.getElementById("cancel-button");
//     progress_div = document.getElementById("match-upload-progress");
//     error_element = document.getElementById("match-upload-error")
// }

/**
 * This class manages the display of progress data during upload as well as handles error display
 */
class ProgressManager {

    /**
     * Constructor
     */
    constructor(){
        this.interval = null;
    }

    /**
     * Initialize ProgressManager and start rendering progress data
     * 
     * @returns {void}
     */
    init(){
        this.startRenderProgress();
        //this.interval = setInterval(this.renderProgress(this), 1000);
    }

    /**
     * Stop progress rendering interval and optionally display an error message
     * 
     * @param {string} [err] optional error message 
     * 
     * @returns {void}
     */
    stopRenderProgress(err = null){
        clearInterval(this.interval);
        this.showUploadForm();
        if(err){
            this.setError(err);
        }
    }

    /**
     * Start progress rendering interval
     * 
     * @returns {void}
     */
    startRenderProgress(){
        this.stopRenderProgress();
        this.interval = setInterval(this.renderProgress(this), 1000);
    }

    /**
     * Get progress data of the current upload
     * 
     * @returns {Promise} promise with progress data
     */
    getProgress(){
        var request = new Request(constants.endpoints.load_progress,
            {
                method: "POST",
                credentials: "same-origin"
            }
        )
        return new Promise((resolve, reject) => {
            fetch(request)
            .then(response => {return response.json()})
            .then(data => {
            resolve(data);
            // console.log('progress data: ');
            // console.log(data);
            // if(data.error){
            //     progress_end_element.innerHTML = 'an error occured: <br>' + data.error_message;
            //     clearInterval(interval);
            // }else{
            //     render_progress(data.message, data.started, data.finished, data.start_time, data.end_time);
            // }
        })
        .catch(err => {
            console.log('err');
            console.log(err);
            reject(err);
        });
        })
        
    }

    /**
     * Render progress data
     * 
     * @param {ProgressManager} the_this should be passed the 'this' variable
     */
    renderProgress(the_this){
        //console.log("This: ");
        //console.log(this);
        the_this.getProgress()
        .then(progress_data => {

            if(!progress_data){
                the_this.showUploadForm();
            }else{
                this.showProgressDiv(progress_data);
            }

            console.log("progress data: ");
            console.log(progress_data);
        })
        .catch(err => {
            console.error("Could not fetch progress data");
            console.error(err);
        });
    }

    /**
     * Hide progress data display and show the upload form
     * 
     * @returns {void}
     */
    showUploadForm(){
        match_upload_form.style.display = "flex";
        cancel_button.style.display = "none";
        progress_div.style.display = "none";
    }

    /**
     * Clear error message
     * 
     * @returns {void}
     */
    clearError(){
        error_element.innerHTML = "";
    }

    /**
     * Set error message and clear success message
     * 
     * @param {string} str string to set the error message to
     */
    setError(str){
        this.clearSuccessMessage();
        error_element.innerHTML = str;
    }

    /**
     * Clear success message
     * 
     * @returns {void}
     */
    clearSuccessMessage(){
        success_element.innerHTML = "";
    }

    /**
     * Set success message and clear error message
     * 
     * @param {string} str message
     * 
     * @returns {void}
     */
    setSuccessMessage(str){
        this.clearError();
        success_element.innerHTML = str
    }

    /**
     * Hide upload form and show progress data display
     * 
     * @param {Object} progress_data progress data
     */
    showProgressDiv(progress_data){
        match_upload_form.style.display = "none";
        cancel_button.style.display = "flex";
        progress_div.style.display = "flex";
    }
}

/**
 * Handles uploading
 */
class UploadManager {

    constructor(progressManager){
        this.progressManager = progressManager;
    }

    /**
     * Upload the 'match-upload-file' file
     * 
     * @returns {void}
     */
    uploadMatches(){
        this.progressManager.clearError();

        let file = document.getElementById('match-upload-file').files[0];
        const write_logs = document.getElementById('write-logs').checked;
    
        if(file === undefined){
            this.progressManager.setError('Please select a file');
            return;
        }
    
        const formData = new FormData();
        formData.append('matches-file', file);
        console.log('write_logs: ');
        console.log(write_logs);
        formData.append('write-logs', write_logs);
    
        var request = new Request(constants.upload_matches,
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
                this.progressManager.stopRenderProgress(data.error);
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
            this.progressManager.stopRenderProgress("an error occured: " + err);
            //clearInterval(interval);
    
            //progress_end_element.innerHTML = 'an error occured: ' + err;
        });
    }

    /**
     * Enable the submit button
     */
    enableSubmitButton(){
        match_upload_form_submit_button.disabled = false;
    }

    /**
     * Disable the submit button
     */
    disableSubmitButton(){
        match_upload_form_submit_button.disabled = true;
    }
}

//let error_element = document.getElementById('match-upload-error');

//let progress_end_element = document.getElementById('progress-end');

//progressDiv = document.getElementById('match-upload-progress');
//progressDiv.style.display = 'none';

//let interval;

let constants = [];

let progressManager = new ProgressManager();
let uploadManager = new UploadManager(progressManager);

uploadManager.disableSubmitButton();

window.onload = () => {
    get_constants()
    .then(_constants => {
        constants = _constants;
        uploadManager.enableSubmitButton();
        progressManager.init();

        //interval = setInterval(get_progress, 1000);
        //clear_progress();
    });
}

function get_constants(){
    return new Promise((resolve, reject) => {
        fetch("../wp-content/plugins/match-upload/php/endpoints/get_const.php")
        .then(response => response.json())
        .then(data => {
            resolve(data);
        })
        .catch(err => {
            reject(err);
        });
    })
    
}

function cancel_upload(){
    //console.log('canceling
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

function uploadMatches(){

    uploadManager.uploadMatches();

    // error_element.innerHTML = '';
    // let file = document.getElementById('match-upload-file').files[0];
    // const write_logs = document.getElementById('write-logs').checked;

    // if(file === undefined){
    //     error_element.innerHTML = 'Please select a file';
    //     return;
    // }

    // const formData = new FormData();
    // formData.append('matches-file', file);
    // console.log('write_logs: ');
    // console.log(write_logs);
    // formData.append('write-logs', write_logs);

    // var request = new Request('" . $UPLOAD_MATCHES_ACTION_URL . "',
    //     {
    //         method: 'POST',
    //         body: formData,
    //         credentials: 'same-origin'
    //         // headers: {
    //         //     'Content-Type': 'multipart/form-data'
    //         // }
    //     }
    // )
    
    
    // //let interval = setInterval(get_progress, 1000);
    // //console.log('interval: ');
    // //console.log(interval);
    
    // //clear_progress();

    // fetch(request)
    // .then(response => {return response.json()})
    // .then(data => {
    //     console.log(data);
    //     if(data.error !== ''){
    //         progress_end_element.innerHTML = 'an error occured: <br>' + data.error;
    //         clearInterval(interval);
    //     }
    //     if(data.success){
    //         progress_end_element.innerHTML = 'upload complete!';
    //     }
    //     //clearInterval(interval);
    //     //clear_progress();
    //     //progress_end_element.innerHTML = 'sucessfully added products, you may now leave this page';
    // })
    // .catch(err => {
    //     //console.log('ABCD');
    //     console.log(err);
    //     clearInterval(interval);

    //     progress_end_element.innerHTML = 'an error occured: ' + err;
    // });
}



function get_progress(){
    var request = new Request(constants.endpoints.load_progress,
        {
            method: 'POST',
            credentials: 'same-origin'
        }
    )
    return new Promise((resolve, reject) => {
        fetch(request)
        .then(response => {return response.json()})
        .then(data => {
        resolve(data);
        // console.log('progress data: ');
        // console.log(data);
        // if(data.error){
        //     progress_end_element.innerHTML = 'an error occured: <br>' + data.error_message;
        //     clearInterval(interval);
        // }else{
        //     render_progress(data.message, data.started, data.finished, data.start_time, data.end_time);
        // }
    })
    .catch(err => {
        console.log('err');
        console.log(err);
        reject(err);
    });
    })
    
}

function render_progress(message, started, finished, start_time, end_time){
    indexElement = document.getElementById('progress-index');
    titleElement = document.getElementById('progress-title');
    statusElement = document.getElementById('progress-status');
    cancelButton = document.getElementById('cancel-button');
    progressTimeElement = document.getElementById('progress-time');
    progressEndDiv = document.getElementById('match-upload-end');
    messageElement = document.getElementById('progress-message');
    

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
        messageElement.innerHTML = message;
        //document.getElementById('progress-index').innerHTML = index;
        //document.getElementById('progress-title').innerHTML = title;
        if(status === true){
            //document.getElementById('progress-status').innerHTML = 'Product doesn\'t exist, adding';
        }else{
            //document.getElementById('progress-status').innerHTML = 'Product already exists, updating';
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