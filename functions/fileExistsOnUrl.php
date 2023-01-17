<?php

    /**
     * Check if file exists on given url
     * 
     * @param string $url url to check
     * 
     * @return bool true if found, false if not
     */
    function fileExistsOnUrl(string $url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if( $httpCode == 200 ){return true;}
        return false;
    }


    /**
     * Run fileExistsOnUrl multiple times
     * 
     * @param string[] urls to check
     * 
     * @return string|bool returns first url that is found, or false if none are found
     */
    function fileExistsOnUrlMultiple(array $urls){
        foreach($urls as $url){
            if(fileExistsOnUrl($url)){
                return $url;
            }
        }
        return false;
    }

    /**
     * Old implementation of fileExistsOnUrl() that uses @get_headers() instead of cURL
     * 
     * @param string $url url to check
     * 
     * @return bool true if found, false if not
     */
    function fileExistsOnUrlOld(string $url){
        $file = $url;
        $file_headers = @get_headers($file);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        else {
            return true;
        } 
    }

    