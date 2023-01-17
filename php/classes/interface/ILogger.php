<?php

    interface ILogger {

        /**
         * Logger interface
         */

        /**
         * Log data
         * 
         * @param string $data data to log
         * 
         * @return void
         */
        public function log(string $data);
    }