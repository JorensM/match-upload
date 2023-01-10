<?php

    //Classes
    require_once("abstract/AbstractDatastoreManager.php");

    //Functions
    require_once(__DIR__."/../functions/echoNl.php");

    class SessionDataManager extends AbstractDatastoreManager{

        /**
         * Constructor. Must pass $_SESSION to constructor
         * 
         * @param array &$session_var must pass $_SESSION here
         */
        public function __construct(array &$session_var = null){
            session_start();
            parent::__construct($_SESSION);
            session_write_close();
        }

        protected function beforeGet(){
            session_start();
            $this->setDatastoreVar($_SESSION);
        }
        protected function afterGet(){
            session_write_close();
        }

        protected function beforeSet(){
            session_start();
            $this->setDatastoreVar($_SESSION);
        }
        protected function afterSet(){
            session_write_close();
        }
    }