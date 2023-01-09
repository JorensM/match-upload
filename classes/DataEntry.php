<?php

    require_once("IDataEntry.php");

    class DataEntry implements IDataEntry {

        private bool $required;
        private string $type;
        private $value;

        private IChecker $checker;
        private IGetter $getter;

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

        public function __construct(
            $properties,
            IChecker $checker
        ){
            $this->required = $properties["required"];
            $this->type = $properties["type"];
            $this->value = $properties["value"];

            $this->checker = $checker;
        }

        public function getValue(){
            
        }

        public function setValue($val){
            return $this->checker->check("required", $this->required, $this->value);
        }

        public function setType(string $type){

        }

        public function getType(){

        }

        public function setRequired(bool $val){

        }

        public function getRequired(){
            
        }
    }

    

    