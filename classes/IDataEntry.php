<?php

    interface IGetter {
        public function get($target);
    }

    interface IChecker {

        public function __construct();

        public function check(...$args);
    }

    interface IDataEntry {
        public function __construct($properties, IChecker $checker);

        public function setValue($value);

        //public function check($target);

        // public function getValue();
        // public function setValue($value);
        // public function getRequired();
        // public function setRequired(bool $value);
        // public function getType();
        // public function setType(string $type);
    }