<?php

    /**
     * Format exception into a "caught exception" string.
     * 
     * @param Exception $e Exception to format
     * @return string Formatted string 
     */
    function strCaughtException(Exception $e){
        $e_message = $e->getMessage(); //Exception message
        $e_stack_trace = nl2br($e->getTraceAsString()); //Stack trace

        //final string to be returned
        $final_str = 
            "<br>Caught exception: " . 
            $e_message . 
            "<br>Stack trace: <br>" . 
            $e_stack_trace;

        return $final_str;
    }