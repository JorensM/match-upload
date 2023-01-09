<?php

    interface IDatastoreManager {

        public function getValues($key);
        public function setValues($key, $key_value_pairs);

    }