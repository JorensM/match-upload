<?php

    require_once("abstract/AbstractImporter.php");
    require_once("MatchObjectToProductSettings.php");

    class MatchObjectToProductImporter extends AbstractImporter {


        protected function importAction($data){
            
        }

        protected function generateSettingsObject(){
            return new MatchObjectToProductSettings();
        }

    }