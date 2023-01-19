
//Element references
//TODO: move them to the classes that use them
let match_upload_form = document.getElementById("match-upload-form");
let match_upload_form_submit_button = document.getElementById("match-upload-submit");
let cancel_button = document.getElementById("cancel-button");
let progress_div = document.getElementById("match-upload-progress");
let progress_message = document.getElementById("progress-message");
let error_element = document.getElementById("match-upload-error");
let success_element = document.getElementById("match-upload-success");
let neutral_element = document.getElementById("match-upload-neutral");
let progress_time = document.getElementById("progress-time");

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
        console.log("stopping render progress");
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
        console.log("starting render progress");
        this.interval = setInterval(() => this.renderProgress(this), 1000);
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
        console.log("rendering progress");
        the_this.getProgress()
        .then(progress_data => {

            console.log(progress_data);
            if(!progress_data || progress_data.finished){
                the_this.showUploadForm();
            }else{
                this.showProgressDiv(progress_data);
            }
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
     * Set error message and clear other messages
     * 
     * @param {string} str string to set the error message to
     */
    setError(str){

        this.clearNeutralMessage();
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
     * Set success message and clear other messages
     * 
     * @param {string} str message
     * 
     * @returns {void}
     */
    setSuccessMessage(str){

        this.clearNeutralMessage();
        this.clearError();
        success_element.innerHTML = str;

    }

    /**
     * Clear the neutral message
     * 
     * @returns {void}
     */
    clearNeutralMessage(){

        neutral_element.innerHTML = "";

    }

    /**
     * Set neutral message and clear other messages
     * 
     * @param {string} str message
     */
    setNeutralMessage(str){

        this.clearError();
        this.clearSuccessMessage();
        neutral_element.innerHTML = str;

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
        progress_message.innerHTML = progress_data.message;
        progress_time.innerHTML = progress_data.end_time - progress_data.start_time;

    }
}

/**
 * Handles uploading
 */
class UploadManager {

    /**
     * constructor
     * 
     * @param {ProgressManager} progressManager reference to the ProgressManager instance
     */
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
    
        var request = new Request(constants.endpoints.upload_matches,
            {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }
        )

        this.progressManager.setNeutralMessage("Starting upload, please wait...");

        fetch(request)
        .then(response => {return response.json()})
        .then(data => {

            console.log(data);

            if(data.error !== ''){

                this.progressManager.stopRenderProgress(data.error);

            }
            if(data.success){

                this.progressManager.setSuccessMessage("upload complete!");

            }
        })
        .catch(err => {

            console.log(err);
            this.progressManager.stopRenderProgress("an error occured: " + err);

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

function uploadMatches(){

    uploadManager.uploadMatches();

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

