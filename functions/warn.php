<?php

    /**
     * Triggers a warning
     * 
     * @param string $message message
     * @param any $object object where warning occured (optional)
     * @param string $function_name name of function where warning occured (optional)
     */
    function warn(string $message, $object = null, string $function_name = null){
        $message = formatExceptionMessage($message, $object, $function_name);

        trigger_error($message, E_WARNING);
    }