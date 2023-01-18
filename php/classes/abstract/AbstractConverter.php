<?php

    require_once(__DIR__."/../interface/IConverter.php");
    require_once(__DIR__."/../interface/ISettings.php");
    require_once("AbstractHasSettings.php");

    abstract class AbstractConverter extends AbstractHasSettings implements IConverter {

        private $output;

        public function __construct(array $settings = self::default_settings){

            $this->initSettings();
            $this->setSettings($settings);

        }

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

    }