<?php

    require_once(__DIR__."/../functions/formatExceptionMessage.php");

   
    class MyException extends Exception {

         /**
             * Extended Exception class for formatted messages.
        */

        /**
         * Constructor method. Calls parent constructor with formatted message 
         * 
         * @param string message Message
         * @param any $object (optional) Object where error occured
         * @param any $function_name (optional) name of function/method where error occured
         */
        public function __construct(string $message, $object = null, string $function_name = null){

            $message = formatExceptionMessage($message, $object, $function_name);

            parent::__construct($message);
        }
    }