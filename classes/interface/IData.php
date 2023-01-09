<?php

    interface IData {

        public function __construct(IDataEntry $entry_prototype);

        public function entry($key);

        public function allEntries();

    }