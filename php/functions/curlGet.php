<?php

    require_once(__DIR__."/../classes/MyException.php");

    /**
     * Make a curl GET request
     * 
     * @param string $url target url
     * @param array $params key/value pairs of GET parameters
     * @param string $auth_username (optional) username for basic auth
     * @param string $auth_password (optional) password for basic auth
     */
    function curlGet(string $url, array $params, string $auth_username = null, string $auth_password = null){

        //GET parameters converted to to string
        $params_str = "?";
        foreach($params as $key => $value){
            $params_str .= $key . "=" . $value . "&";
        }
        //Remove last "&" character from param string
        $params_str = substr($params_str, 0, -1);


        //Final url (url + params)
        $final_url = $url . $params_str;

        //echo "making get request: " . $final_url;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $final_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
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