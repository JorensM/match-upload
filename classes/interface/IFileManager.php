<?php

    interface IFileManager {

        /**
         * Validates file based on params. Should validate things like filetype, file size, etc.
         * 
         * @param string $name name of file
         * @param array $params (optional) parameters
         * 
         * @return bool true if validation passed, throws error if didn't pass
         */
        public function validateFile(string $name, array $params = null);

        /**
         * Wrapper for fopen();
         * 
         * @param string $name name of file
         * @param string (default "r") $mode mode, such as "r" and "write"
         * 
         * @return any file handle returned by fopen();
         */
        public function openFile(string $name, string $mode = "r");

        /**
         * Wrapper for fclose();
         * 
         * @param any $handle file handle returned by openFile() or fopen()
         * 
         * @return bool return value of fclose() (true on success and false on failure)
         */
        public function closeFile($handle);

    }