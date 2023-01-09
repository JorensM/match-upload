<?php

    require_once(__DIR__."/../interface/IDatastoreManager.php");
    require_once(__DIR__."/../MyException.php");

    abstract class AbstractDatastoreManager implements IDatastoreManager {
        private array $data;

        private IData $data_prototype;

        public function getValues($key){

            $is_data_set = isset($this->data[$key]);

            if(!$is_data_set){
                throw new MyException("Could not find data object by key '$key'", $this, __METHOD__);
            }

            $data_arr = $this->data[$key]->allEntries();

            $values = [];

            foreach($data_arr as $arr_value){
                array_push($values, $arr_value->getValue());
            }

            return $values;
        }

        public function setValues($key, $key_value_pairs){

            $data = null;

            $is_data_set = isset($this->data[$key]);

            if(!$is_data_set){
                $this->data[$key] = clone $this->data_prototype;
            }else{
                
            }

            $data = $this->data[$key];

            //$data_arr = $this->data[$key]->allEntries();

            

            foreach($key_value_pairs as $key => $value){
                $data->entry($key)->setValue($value);
            }

        }

        public function __construct(IData $data_prototype){
            $this->data_prototype = $data_prototype;

            $this->data = [];
        }
    }