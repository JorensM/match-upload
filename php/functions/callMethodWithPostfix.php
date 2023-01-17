<?php


    /**
     * Calls a class method with the name "$prefix + $postfix"
     *  
     * @param any $_var the $this variable of the caller class
     * @param $prefix Method name's prefix
     * @param $postfix Method name's postfix. The first letter of postfix gets converted to uppercase
     * @param ...$args Arguments passed to the called method
     * @return any Returns called method's return value, or Exception if method was not found
     */
    function callMethodWithPostfix($_this, $prefix, $postfix, ...$args){
        $method_name_prefix = $prefix;
        $method_name_postfix = ucwords($postfix);//Convert postfix first letter to uppercase
        
        $method_name = $method_name_prefix . $method_name_postfix;

        $method_exists = method_exists($_this, $method_name);


        if($method_exists){
            //If method exists, call it and returns its return value
            return $_this->$method_name(...$args);
        }
        else{
            //If method doesn't exist, throw exception
            throw new Exception("callMethodWithPostfix: Could not find method with name $method_name");
        }
    }

    