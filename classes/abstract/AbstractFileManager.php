<?php

    require_once(__DIR__."/../interface/IFileManager.php");

    abstract class AbstractFileManager implements IFileManager {

        public abstract function validateFile(string $name, array $params = null);
        public abstract function openFile(string $name, string $mode = "r");
        
        public function closeFile($handle){
            return fclose($handle);
        }

    }