<?php

    require_once(__DIR__."/../interface/IImporter.php");
    require_once(__DIR__."/../interface/IHasSettings.php");

    abstract class AbstractImporter implements IIimporter, IHasSettings{

        private ISettings $_settings;

        public function __construct(array $settings){
            $this->_settings = $this->generateSettingsObject();

            $this->_settings->set($settings);
        }

        public function import($data){
            return $this->importAction($data);
        }

        public function &settings(){
            return $this->_settings;
        }

        abstract protected function generateSettingsObject();

        abstract protected function importAction($data);

    }