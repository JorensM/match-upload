<?php

    /**
     * Convert a string to lowercase and strip spaces, also replace special characters with regular characters
     * FC Barcelona = fcbarcelona
     * 
     * @param string $str = string to convert
     * 
     * @return string converted string
     */
    function cleanStr(string $str) {
        $new_string = $str;
        $new_string = replace_umlauts($new_string);
        $new_string = str_replace("&", "", $new_string);
        $new_string = urlencode(strtolower(str_replace(" ","",preg_replace("/([a-z])[a-z]+;/i", "$1", htmlentities($new_string)))));
        $new_string = preg_replace('/[^A-Za-z0-9\-]/', '', $new_string);
        return $new_string;
    }

    /**
     * Same as cleanStr, except capitalized letters are kept capitalized
     * FC Barcelona = FCBarcelona
     * 
     * @param string $str = string to convert
     * 
     * @return string converted string
     */
    function cleanStrCaps(string $str){
        $new_string = $str;
        $new_string = replace_umlauts($new_string);
        $new_string = str_replace("&", "", $new_string);
        $new_string = urlencode(str_replace(" ","",preg_replace("/([a-z])[a-z]+;/i", "$1", htmlentities($new_string))));
        $new_string = preg_replace('/[^A-Za-z0-9\-]/', '', $new_string);
        return $new_string;
    }