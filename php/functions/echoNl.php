<?php

    require_once("warn.php");

    /**
     * Echoes string in a new line.
     * 
     * @param string $str String to echo
     * 
     * @return void
     */
    function echoNl($str){

        $param_type = gettype($str);

        if($param_type === "string"){
            echo "<br>";
            echo $str;
        } else{
            warn("Invalid \$str type (must be string, $param_type passed)", null, __FUNCTION__);
        }
        
    }