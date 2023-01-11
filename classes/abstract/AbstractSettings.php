<?php

    require_once(__DIR__."/../interface/ISettings.php");

    abstract class AbstractSettings implements ISettings {

        private array $settings;

        protected array $required_fields = [];

        protected array $schema;

        public function set(array $settings){
            $this->settings = $settings;
        }

        public function get(){
            return $this->settings;
        }

        public function validateRequired(){
            $settings = $this->get();

            $missing_settings = [];

            foreach($this->required_fields as $required_field){

                $is_required_setting_set = array_key_exists($required_field, $settings);

                if(!$is_required_setting_set){
                    array_push($missing_settings, $required_field);
                    //throw new MyException("Missing required setting")
                }
            }

            //If there are any missing settings, throw error and output missing setting names
            if(empty($missing_settings)){
                return true;
            }else{
                throw new MyException("Missing required settings: " . implode(" | ", $missing_settings), $this, __METHOD__);
            }
        }

        public function getSchema(){
            return $this->schema;
        }

    }