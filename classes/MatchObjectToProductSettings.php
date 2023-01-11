<?php

    require_once("abstract/AbstractSettings.php");

    class MatchObjectToProductSettings extends AbstractSettings {
        protected array $schema = [
            "limit" => "int - limit importing to first n objects"
        ];
    }