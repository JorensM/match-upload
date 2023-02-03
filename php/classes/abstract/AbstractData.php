<?php

    require_once(__DIR__."/../interface/IData.php");

    abstract class AbstractData implements IData {

        /**
         * Abstract Data class.
         * 
         * @var array $fields
         * @var array $data
         */
        private array $fields;
        public array $data;

        /**
         * Constructor
         * 
         * @param array $data data
         */
        public function __construct(array $data){

            $this->data = $data;

            //Set fields
            foreach($data as $key => $value){
                array_push($this->fields, $key);
            }

            //$this->data = $data;
        }

        public function overwrite(IData $to_merge_with){

        }

        protected function setFields(array $fields){
            $this->fields = $fields;
        }

        protected function getfields(){
            return $this->fields;
        }

        public function set(string $key, $value){
            $this->data[$key] = $value;
        }

        public function get(string $key){
            if(isset($this->data[$key])){
                return $this->data[$key];
            }
            throw new MyException("Could not get data entry by key '$key': not found", $this, __METHOD__);
        }

    }