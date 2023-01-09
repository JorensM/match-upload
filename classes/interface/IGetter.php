<?php

    interface IGetter {
        /**
         * Getter interface.
         * To create a get function, in the extended class create a private function, such as getValue($params).
         * Then you can call the getValue() function using get("value", ...$args);
         */

        /**
         * Calls "get + $name" method of this class
         * 
         * @param string $name Postfix of the method to call. So if $name = "hello", getHello() will be called
         * @param any ...$args Args to be passed to the called method
         * 
         * @return any Return value of the called method
         */
        public function get($name, ...$args);
    }