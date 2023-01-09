<?php

    require_once("./abstract/AbstractDataEntryParams.php");

    class DataEntryGetter extends AbstractGetter {

        private function getValue(AbstractDataEntryParams $params){
            return $params->value;
        }

    }