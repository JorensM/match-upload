<?php

    require_once(__DIR__."/../interface/ISettings.php");

    abstract class AbstractSettings implements ISettings {

        /**
         * Abstract settings class
         * 
         * @var array $settings settings themselves
         * 
         * @var array $required_fields fields that are required
         */

        private array $settings;

        protected array $required_fields = [];
        protected array $schema;

        /**
         * Set settings
         * 
         * @param array $settings new settings
         * 
         * @return void
         */
        public function set(array $settings){
            $this->settings = $settings;
        }

        /**
         * Get settings
         * 
         * @return array current settings
         */
        public function get(){
            return $this->settings;
        }

        /**
         * Validate required settings. Throws error if required settings are missing
         * 
         * @return true if required settings are set. Throws error if required settings are missing
         */
        public function validateRequired(){
            $settings = $this->get();

            $missing_settings = [];

            foreach($this->required_fields as $required_field){

                $is_required_setting_set = array_key_exists($required_field, $settings);

                if(!$is_required_setting_set){
                    array_push($missing_settings, $required_field);
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