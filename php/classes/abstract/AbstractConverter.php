<?php

    //Classes
    require_once(__DIR__."/../interface/IConverter.php");
    require_once(__DIR__."/../interface/ISettings.php");
    require_once("AbstractHasSettings.php");

    abstract class AbstractConverter extends AbstractHasSettings implements IConverter {

        /**
         * Abstract converter class
         * 
         * @var any $output output that getResult() returns
         */

        private $output;

        /**
         * Constructor
         * 
         * @param array $settings settings that will be added to the ISettings object
         * 
         */
        public function __construct(array $settings = self::default_settings){

            $this->initSettings();
            $this->setSettings($settings);

        }

        /**
         * Set the $output member variable
         * 
         * @param mixed $output value to set to
         * 
         * @return void
         */
        private function setOutput($output){

            $this->output = $output;

        }

        /**
         * Main convert action. Calls convertAction method
         * 
         * @param mixed $from thing to convert
         * 
         * @return void
         */
        public function convert($from){

            $output = $this->convertAction($from);
            $this->setOutput($output);

        }

        /**
         * Returns the result of the latest conversion
         * 
         * @return mixed result of the latest conversion
         */
        public function getResult(){

            return $this->output;

        }

        /**
         * This is the method that gets called by the convert() method. Must be implemented
         * 
         * @param mixed $from data to convert
         * 
         * @return mixed converted data that will get assigned to $output
         */
        abstract protected function convertAction($from);

    }