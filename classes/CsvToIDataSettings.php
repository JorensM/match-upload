<?php

    require_once("abstract/AbstractSettings.php");

    class CsvToIDataSettings extends AbstractSettings {
        protected array $required_fields = ["key_to_column_mappings", "data_object_prototype"];
    }