<?php

    require_once("../interface/IGetter.php");


    abstract class AbstractGetter implements IGetter {

        public function get($name, ...$args){
            try{
                //call method with name "check + $name" and pass args
                return callMethodWithPostfix($this, "get", $name, ...$args);
            } catch (Exception $e){
                echo strCaughtException($e);
            }
        }

    }