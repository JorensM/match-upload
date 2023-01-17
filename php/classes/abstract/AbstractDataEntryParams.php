<?php

    require_once(__DIR__."/../interface/IDataEntryParams.php");

    abstract class AbstractDataEntryParams implements IDataEntryParams {

        /**
         * Params for IDataEntry
         * 
         * @var any $value value of entry
         * @var bool $required whether value is required. null/false = not required
         * @var string $type type of value. null/"any" = any type
         */

        public $value;
        public bool|null $required;
        public string|null $type;

        public function __construct($params_assoc_arr){
            $arr = $params_assoc_arr;

            $this->value = $arr["value"];
            $this->required = $arr["required"];
            $this->type = $arr["type"];
        }
    }