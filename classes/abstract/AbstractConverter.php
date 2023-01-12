<?php

    require_once(__DIR__."/../interface/IConverter.php");
    require_once(__DIR__."/../interface/ISettings.php");
    require_once("AbstractHasSettings.php");

    abstract class AbstractConverter extends AbstractHasSettings implements IConverter {

        private $output;

        //private array $settings;

        //protected ISettings $settings;

        //protected const required_settings = [];
        //protected array $required_settings = [];

        public function __construct(array $settings = self::default_settings){

            //$this->settings = $this->generateSettingsObject();
            $this->initSettings();
            $this->setSettings($settings);
        }

        // public function setSettings(array $settings){

        //     $this->settings->set($settings);

        //     $this->settings->validateRequired();

        //     //$this->settings = $settings;

        //     //$this->validateRequiredSettings();
        // }

        // private function validateRequiredSettings(){

        //     $settings = $this->getSettings();

        //     $missing_settings = [];

        //     foreach($this->required_settings as $required_setting){

        //         $is_required_setting_set = array_key_exists($required_setting, $settings);

        //         if(!$is_required_setting_set){
        //             array_push($missing_settings, $required_setting);
        //             //throw new MyException("Missing required setting")
        //         }
        //     }

        //     //If there are any missing settings, throw error and output missing setting names
        //     if(empty($missing_settings)){
        //         return true;
        //     }else{
        //         throw new MyException("Missing required settings: " . implode(" | ", $missing_settings), $this, __METHOD__);
        //     }
        // }

        // public function getSettings(){
        //     return $this->settings->get();
        // }

        public function convert($from){
            $output = $this->convertAction($from);
            $this->setOutput($output);
        }

        public function getResult(){
            return $this->output;
        }

        abstract protected function convertAction($from);

        private function setOutput($output){
            $this->output = $output;
        }

        /**
         * Must be implemented and must return ISettings object
         * 
         * @return ISettings 
         */
        //abstract protected function generateSettingsObject();

    }