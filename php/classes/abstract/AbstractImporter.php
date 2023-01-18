<?php

    require_once(__DIR__."/../interface/IImporter.php");
    require_once(__DIR__."/../interface/IHasSettings.php");

    abstract class AbstractImporter extends AbstractHasSettings implements IIimporter{

        private ISettings $_settings;

        public function __construct(array $settings){
            //$this->_settings = $this->generateSettingsObject();
            $this->initSettings();
            $this->setSettings($settings);
        }

        public function import($data, ...$args){
            $this->validateAction($data);
            return $this->importAction($data, ...$args);
        }

        //public function &settings(){
            //return $this->_settings;
        //}

        //abstract protected function generateSettingsObject();
        
        /**
         * Import action that will be called by import().
         * Throws error on failure
         * 
         * @param string $data data to import
         * 
         * @return bool true on success. 
         */
        abstract protected function importAction(array $data, ...$args);

        /**
         * Validation action used to validate whether specified data is correct.
         * Gets called in import() method before importAction()
         * Should throw error on validation fail, and return true on success.
         * 
         * @param array $data data to be imported
         * 
         * @return bool true on success. 
         */
        abstract protected function validateAction(array $data);

    }