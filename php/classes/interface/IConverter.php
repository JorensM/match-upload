<?php

    require_once("IHasSettings.php");

    interface IConverter extends IHasSettings {
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

        /**
         * Convert file
         * 
         * @param any $from file to convert from
         * 
         * @return void
         */
        public function convert($from);

        /**
         * Retrieve the result of the latest conversion
         * 
         * @return any result of conversion
         */
        public function getResult();
    }