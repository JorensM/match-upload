<?php

    require_once("IHasGetters.php");
    require_once("IDataEntryParams.php");

    interface IDataEntry{
        public function __construct(IDataEntryParams $properties, IChecker $checker);

        public function setValue($value);
        public function getValue();

        public function setRequired(bool $required);
        public function getRequired();

        public function setType(string $type);
        public function getType();

        //public function check($target);

        // public function getValue();
        // public function setValue($value);
        // public function getRequired();
        // public function setRequired(bool $value);
        // public function getType();
        // public function setType(string $type);
    }