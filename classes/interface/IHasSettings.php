<?php

    interface IHasSettings {

        // /**
        //  * Interface for classes that have settings
        //  */

        // /**
        //  * Sets settings
        //  * 
        //  * @param array $settings settings
        //  * 
        //  * @return void
        //  */
        // public function setSettings(array $settings);

        // /**
        //  * Returns settings
        //  * 
        //  * @return array settings
        //  */
        // public function getSettings();

        /**
         * Returns the settings object
         * 
         * @return ISettings settings
         */
        public function &settings();

    }