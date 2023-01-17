<?php

    interface IHasGetters {
        /**
         * Used for classes that have getters to create a common "get()" method.
         */

        /**
         * Calls the classes "get + $name" method. For example if $name = "hello", this method will call
         * a method called "getHello()"
         * 
         * @param string $name Postfix of the "get...()" method to call.
         * 
         * @return any Return value of the called method
         */
        public function get(string $name);
    }