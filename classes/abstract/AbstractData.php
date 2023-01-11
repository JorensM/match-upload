<?php

    require_once(__DIR__."/../interface/IData.php");

    abstract class AbstractData implements IData {

        private array $fields;

        public array $data;

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

    }