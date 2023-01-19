<?php

    require_once(__DIR__."/../interface/IFileManager.php");

    abstract class AbstractFileManager implements IFileManager {

        /**
         * Abstract file manager class
         */

        /**
         * Close stream. Basically a wrapper for fclose()
         * 
         * @param $handle stream
         * 
         * @return mixed return value of fclose($handle)
         */
        public function closeFile($handle){
            return fclose($handle);
        }

        
        /**
         * Validates file based on params. Should throw error if validation failed
         * 
         * @param string $name name of file
         * @param array $params params
         * 
         * @return bool true on validation pass. Throws error on validation fail
         */
        public abstract function validateFile(string $name, array $params = null);

        /**
         * Opens and returns a stream to a file
         * 
         * @param string $name name of file
         * @param string $mode ("r") mode
         * 
         * @return mixed stream of file
         */
        public abstract function openFile(string $name, string $mode = "r");

    }