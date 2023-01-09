<?php

    $dir = __DIR__."/../../";

    require_once($dir."classes/interface/IChecker.php");
    require_once($dir."functions/callMethodWithPostfix.php");
    require_once($dir."functions/strCaughtException.php");

    abstract class AbstractChecker implements IChecker {

        //private IDataEntry $entry;

        public function __construct(){
            //$this->entry = $entry;
        }

        public function check($name, ...$args){

            try{
                //call method with name "check + $name" and pass args
                return callMethodWithPostfix($this, "check", $name, ...$args);
            } catch (Exception $e){
                echo strCaughtException($e);
            }

            // $target = array_shift($args);

            // $fn_name = "check" . ucwords($target);

            // if(method_exists($this, $fn_name)){
            //     return $this->$fn_name(...$args);
            // }
            // else{
            //     throw new Exception("DataEntryChecker: Could not find checker function with name $fn_name");
            // }
        }

        // private function checkRequired($requiredValue, $value){
        //     if($requiredValue && $value === null){
        //         return false;
        //     }
        //     return true;
        // }
    }