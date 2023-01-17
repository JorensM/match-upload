<?php

    require_once("abstract/AbstractSettings.php");

    class MatchObjectToProductSettings extends AbstractSettings {

        protected array $required_fields = ["limit", "batch_size", "session", "logger"];
        
        protected array $schema = [
            "limit" => "int - limit importing to first n objects, useful for debug",
            "batch_size" => "int - amount of items to import per batch, recommended < 100",
            "session" => "SessionDataManager - will write info to session",
            "logger" => "ILogger - logging tool to use"
        ];
    }