<?php    
    
    require_once("./abstract/AbstractChecker.php");
    require_once("../functions/callMethodWithPostfix.php");
    require_once("../functions/strCaughtException.php");

    class DataEntryChecker extends AbstractChecker {

        //private IDataEntry $entry;

        public function __construct(){
            //$this->entry = $entry;
        }

        // public function check($name, ...$args){

        //     try{
        //         //call method with name "check + $name" and pass args
        //         return callMethodWithPostfix($this, "check", $name, ...$args);
        //     } catch (Exception $e){
        //         echo strCaughtException($e);
        //     }

        //     // $target = array_shift($args);

        //     // $fn_name = "check" . ucwords($target);

        //     // if(method_exists($this, $fn_name)){
        //     //     return $this->$fn_name(...$args);
        //     // }
        //     // else{
        //     //     throw new Exception("DataEntryChecker: Could not find checker function with name $fn_name");
        //     // }
        // }

        private function checkRequired($requiredValue, $value){
            if($requiredValue && $value === null){
                return false;
            }
            return true;
        }
    }