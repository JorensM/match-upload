<?php

    interface IData {

        /**
         * Constructs object and assigns data to it. Once constructed, new fields cannot be added,
         * only the fields passed in the $data param can be accessed
         * 
         * @param array $data data
         */
        public function __construct(array $data);

        /**
         * Overwrites current objects data with another IData objects data.
         * If a fields is omitted, it does not get overwritten and remains the way it was.
         * 
         * @param IData $to_overwrite_with data object to overwrite with
         */
        public function overwrite(IData $to_overwrite_with);

        /**
         * Sets value of data entry by key
         * 
         * @param string $key key of entry
         * @param any $value value to set entry to
         */
        public function set(string $key, $value);

        /**
         * Get entry by key. Throws error if entry not found
         * 
         * @param string $key key of entry
         */
        public function get(string $key);

    }