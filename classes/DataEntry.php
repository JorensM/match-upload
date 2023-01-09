<?php

    require_once("abstract/AbstractDataEntry.php");

    class DataEntry extends AbstractDataEntry {

        private bool $required;
        private string $type;
        private $value;

        private IChecker $checker;

        // public function check($target){
        //     $fn_name = "check" . ucwords($target);

        //     if(method_exists($this, $fn_name)){
        //         return $this->$fn_name();
        //     }
        //     else{
        //         throw new Exception("DataEntry: Could not find checker function with name $fn_name");
        //     }
        // }

        private function checkRequired(){
            echo "Checking required";
        }

        // public function getValue(){
            
        // }

        // public function setValue($val){
        //     return $this->checker->check("required", $this->required, $this->value);
        // }

        // public function setType(string $type){

        // }

        // public function getType(){

        // }

        // public function setRequired(bool $val){

        // }

        // public function getRequired(){
            
        // }
    }

    

    