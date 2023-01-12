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
            $params_str .= $key . "=" . $value;
        }

        //Final url (url + params)
        $final_url = $url . $params_str;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $final_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        if($auth_username && $auth_password){
            curl_setopt($ch, CURLOPT_USERPWD, "$auth_username:$auth_password");
        }

        $result = curl_exec($ch);

        if(curl_errno($ch)){
            throw new MyException("Error making cURL GET request: " . curl_error($ch), null, __FUNCTION__);
        }

        curl_close($ch);

        return $result;
    }