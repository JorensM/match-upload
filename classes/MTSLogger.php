<?php

    require_once("interface/ILogger.php");

    class MTSLogger implements ILogger {

        private $file_handle;

        /**
         * Constructor
         * 
         * @param any $file_handle file handle to write logs to
         */
        public function __construct($file_handle){
            $this->file_handle = $file_handle;
        }

        public function log(string $data){
            $output = PHP_EOL . $data;
            fwrite($this->file_handle, $output);
        }

        public function close(){
            fclose($this->file_handle);
        }

    }