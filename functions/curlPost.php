<?php

    require_once(__DIR__."/../classes/MyException.php");

    /**
     * Make a curl POST request
     * 
     * @param string $url target url
     * @param array $data data to POST
     * @param string $auth_username (optional) username for basic auth
     * @param string $auth_password (optional) password for basic auth
     */
    function curlPost(string $url, array $data, array $headers = null, string $auth_username = null, string $auth_password = null){
        $debug = false;

        //Final url (url + params)
        $final_url = $url;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $final_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        if($headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        //Echo request body if debug is true
        if($debug){
            echo "\nPOST request body: \n";
            echo "<pre>";
            echo json_encode($data);
            echo "<pre>";
        }
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        if($auth_username && $auth_password){
            curl_setopt($ch, CURLOPT_USERPWD, "$auth_username:$auth_password");
        }

        $result = curl_exec($ch);

        if(curl_errno($ch)){
            throw new MyException("Error making cURL GET request: " . curl_error($ch), null, __FUNCTION__);
        }

        curl_close($ch);

        return json_decode($result, true);
    }