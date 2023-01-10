<?php

    interface IDatastoreManager {

        /**
         * Constructor. Pass in variable where data will be stored
         * 
         * @param array &$datastore_variable Variable where data is stored, such as $_SESSION
         */
        public function __construct(array &$datastore_variable);

        /**
         * Get element from datastore variable.
         * For example get("hello") => $datastore_variable["hello"]
         * 
         * @param string $key key of element
         * 
         * @return any target element
         */
        public function get(string $key);

        /**
         * Set element in datastore variable. 
         * For example set("hello", "123") sets $datastore_variable["hello"] to "123"
         * 
         * @param string $key key of element
         * @param any $value value to set the element to
         * 
         * @return void
         */
        public function set(string $key, $value);

        /**
         * Get an entry of the datastore_variable's element. 
         * For example getEntry("hello", "foo") => $datastore_variable["hello"]["foo"]
         * 
         * @param string $key key of element
         * @param string $entry_key key of element's entry
         * 
         * @return any target entry of specified element
         */
        public function getEntry(string $key, string $entry_key);

        /**
         * Set an entry of the datastore_variable's element. 
         * For example setEntry("hello", "foo", "123") sets $datastore_variable["hello"]["foo"] to "123" 
         * 
         * @param string $key key of element
         * @param string $entry_key key of element's entry
         * @param any $value value to set the entry to
         * 
         * @return void
         */
        public function setEntry(string $key, string $entry_key, $value);

        /**
         * Get multiple entries of the datastore_variable's element. If $entry keys is not specified, all entries will be returned
         * For example getEntries("hello", ["foo", "bar"]) => returns $datastore_var["hello"]["foo"] and ["hello"]["bar"] in an array
         * 
         * @param string $key key of element
         * @param array $entry_keys entries to get
         * 
         * @return any specified entries of specified element
         */
        public function getEntries(string $key, array $entry_keys = null);

        /**
         * Set multiple entries of the datastore_variable's element
         * For example setEntries("hello", ["foo" => "123", "bar" => "321"]) sets $datastore_var["hello"]["foo"] to "123" and ["hello"]["bar"] to "321"
         * 
         * @param string $key key of element
         * @param array $entry_key_value_pairs key value pairs of entries to set
         * 
         * @return void
         */
        public function setEntries(string $key, array $entry_key_value_pairs);

    }