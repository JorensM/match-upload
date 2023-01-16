<?php

    require_once("abstract/AbstractSettings.php");

    class ProductImporterSettings extends AbstractSettings {

        protected array $required_fields = ["limit", "batch_size", "session", "logger", "importBy"];
        
        protected array $schema = [
            "limit" => "int - limit importing to first n objects, useful for debug",
            "batch_size" => "int - amount of items to import per batch, recommended < 100",
            "session" => "SessionDataManager - will write info to session",
            "logger" => "ILogger - logging tool to use",
            "importBy" => "string - 'sku' - variable by which to import (for example with 'sku', product will be created/updated based on sku)"
        ];
    }