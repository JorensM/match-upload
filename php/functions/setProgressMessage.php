<?php

    //Classes
    require_once(__DIR__."/../classes/SessionDataManager.php");
    
    //Enums
    require_once(__DIR__."/../classes/enum/EnumSessionDataElement.php");

    /**
     * Set progress message for match upload
     *  
     * @param SessionDataManager $session session object
     * @param string $message message
     * @param bool $started whether upload has started
     * @param bool $finished whether upload has finished
     * @param int $start_time start time of upload
     * 
     * @return void
     */
    function setProgressMessage(SessionDataManager $session, string $message, bool $started, bool $finished, int $start_time){
        $session->set(
            EnumSessionDataElement::ProgressData,
            [
                "message" => $message,
                "new" => false,
                "started" => $started,
                "finished" => $finished,
                "start_time" => $start_time,
                "end_time" => time(),
                "error" => false,
                "error_message" => ""
            ]
        );
    }