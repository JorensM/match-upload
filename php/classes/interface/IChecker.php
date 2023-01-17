<?php

    interface IChecker {

        public function __construct();

        /**
         * Calls this classes "check + $name" method, if it exists.
         * Throws exception if method doesn't exist
         * 
         * @param string $name postfix of the check method
         * @param any ...$args arguments to pass to the called method
         * 
         * @return any Returns the called method's return value
         */
        public function check($name, ...$args);

    }