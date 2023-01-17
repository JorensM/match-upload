<?php

    interface IDataEntryParams {
        /**
         * Params for IDataEntry
         * 
         * @var any $value value of entry
         * @var bool $required whether value is required. null/false = not required
         * @var string $type type of value. null/"any" = any type
         */

        public function __construct($params_assoc_array);

        // public $value;
        // public bool|null $required;
        // public string|null $type;
    }