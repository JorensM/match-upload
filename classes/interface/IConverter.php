<?php

    interface IConverter {
        /**
         * Interface for converters such as png to jpeg, csv to class etc
         */

        private const default_settings = [
            
        ];

        public function __construct($settings = $this->default_settings);

        public function setSettings($settings);

        public function convert($from);

        public function getResult();
    }