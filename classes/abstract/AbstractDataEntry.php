<?php

    $dir = __DIR__."/../../";

    //Classes
    require_once("AbstractDataEntryParams.php");
    require_once($dir."classes/interface/IDataEntry.php");

    //Functions
    require_once($dir."functions/callMethodWithPostfix.php");
    require_once($dir."functions/strCaughtException.php");
    require_once($dir."functions/echoNl.php");

    abstract class AbstractDataEntry implements IDataEntry {
        private bool $required;
        private string $type;
        private $value;

        private AbstractDataEntryParams $params;

        private IChecker $checker;
        //public IGetter $getter;

        // public function check($target){
        //     $fn_name = "check" . ucwords($target);

        //     if(method_exists($this, $fn_name)){
        //         return $this->$fn_name();
        //     }
        //     else{
        //         throw new Exception("DataEntry: Could not find checker function with name $fn_name");
        //     }
        // }

        public function setValue($value){
            $this->params->value = $value;
        }
        public function getValue(){
            return $this->params->value;
        }

        public function setRequired(bool $required){
            $this->params->required = $required;
        }
        public function getRequired(){
            return $this->params->required;
        }

        public function setType(string $type){
            $this->params->type = $type;
        }

        public function getType(){
            return $this->params->type;
        }

        private function checkRequired(){
            echo "Checking required";
        }

        public function __construct(
            IDataEntryParams $properties,
            IChecker $checker,
        ){
            $this->params = $properties;

            //$this->params->required = false;//$properties["required"];
            //$this->params->type = $properties["type"];
            //$this->params->value = $properties["value"];

            $this->checker = $checker;
            //$this->getter = $getter;
        }
    }