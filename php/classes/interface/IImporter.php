<?php

    require_once("IHasSettings.php");

    interface IIimporter extends IHasSettings{
        
        /**
         * Importer interface used for importing data into databases such as WooCommerce products
         */

        public function __construct(array $settings);

        public function import($data, ...$args);

    }