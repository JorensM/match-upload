<?php

    interface IConverter {
        /**
         * Interface for converters such as png to jpeg, csv to class etc
         */

        public const default_settings = [

        ];

        public function __construct(array $settings = self::default_settings);

        /**
         * Sets settings for the converter. If a required setting is omitted, error is thrown
         * 
         * @param array $settings assoc. array of settings
         */
        public function setSettings(array $settings);

        public function convert($from);

        public function getResult();
    }