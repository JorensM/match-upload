<?php

    interface ISettings {
        
        /**
         * Settings object
         * 
         */


        /**
         * Set settings
         */
        public function set(array $settings);

        /**
         * Get settings
         * 
         * @return array settings
         */
        public function get();

        /**
         * Validate required fields and throw error if required field is missing
         * 
         * @return bool true if validation passed, throws error if not passed
         */
        public function validateRequired();

        public function getSchema();
    }