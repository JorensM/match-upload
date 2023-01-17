<?php

    require_once(__DIR__."/../interface/IDatastoreManager.php");
    require_once(__DIR__."/../MyException.php");

    abstract class AbstractDatastoreManager implements IDatastoreManager {

        /**
         * @var array $datastore_var datastore variable, that can be set to for example $_SESSION in the constructor
         */
        private array $datastore_var;

        public function __construct(array &$datastore_variable){
            $this->datastore_var = &$datastore_variable;
        }

        public function setDatastoreVar(array &$datastore_variable){
            $this->datastore_var = &$datastore_variable;
        }

        public function get(string $key){

            $this->beforeGet();

            $is_element_set = isset($this->datastore_var[$key]);
            
            $elem = $is_element_set ? $this->datastore_var[$key] : null;

            $this->afterGet();

            return $elem;
        }
        public function set(string $key, $value){

            $this->beforeSet();

            $this->datastore_var[$key] = $value;

            $this->afterSet();
        }

        public function getEntry(string $key, string $entry_key){

            $this->beforeGet();

            $is_entry_set = isset($this->datastore_var[$key][$entry_key]);

            $entry = $is_entry_set ? $this->datastore_var[$key][$entry_key] : null;

            $this->afterGet();

            return $entry;

        }
        public function setEntry(string $key, string $entry_key, $value){

            $this->beforeSet();

            $this->datastore_var[$key][$entry_key] = $value;

            $this->afterSet();
        }

        public function getEntries(string $key, array $entry_keys = null){

            $this->beforeGet();

            $output = [];
            if($entry_keys !== null){
                foreach($entry_keys as $entry_key){
                    $is_entry_set = isset($this->datastore_var[$key][$entry_key]);
    
                    $output[$entry_key] = $is_entry_set ? $this->datastore_var[$key][$entry_key] : null;
                }
            }else{
                $is_element_set = isset($this->datastore_var[$key]);
                if($is_element_set && is_array($this->datastore_var[$key])){
                    $output = $this->datastore_var[$key];
                }
            }
            

            $this->afterGet();

            return $output;
        }
        public function setEntries(string $key, array $entry_key_value_pairs){

            $this->beforeSet();

            foreach($entry_key_value_pairs as $entry_key => $entry_value){
                $this->datastore_var[$key][$entry_key] = $entry_value;
            }

            $this->afterSet();
        }

        protected function beforeSet(){

        }
        protected function afterSet(){

        }

        protected function beforeGet(){

        }
        protected function afterGet(){

        }
    }