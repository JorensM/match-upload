<?php

    require_once(__DIR__."/../interface/IHasSettings.php");
    require_once(__DIR__."/../interface/ISettings.php");

    abstract class AbstractHasSettings implements IHasSettings {

        private ISettings $settings;

        protected function initSettings(){
            $this->settings = $this->generateSettingsObject();
        }

        public function setSettings(array $settings){
            $this->settings->set($settings);
            $this->settings->validateRequired();
        }

        public function getSettings(){
            return $this->settings->get();;
        }

        /**
         * Must be implemented and must return an ISettings object
         * 
         * @return ISettings 
         */
        abstract protected function generateSettingsObject();
    }