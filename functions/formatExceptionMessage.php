<?php    
    function formatExceptionMessage(string $message, $object, string $function_name = null){

        $prefix = "";

        if($object !== null){

            $object_class_name = get_class($object);

            $prefix .= $object_class_name;
        }

        if($function_name !== null){
            $prefix .= " " . $function_name . "()";
        }

        $prefix .= ": ";

        $final_message = $prefix . $message;

        return $final_message;
    }