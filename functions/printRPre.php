<?php

    /**
     * calls print_r wrapped in <pre> tags
     */
    function printRPre($obj){
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }