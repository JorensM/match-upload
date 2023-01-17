<?php

    require_once(__DIR__."/../classes/MatchObject.php");

    require_once("matchObjectToProductArray.php");

    /**
     * Convert MatchObjects into arrays supported by ProductImporter
     * 
     * @param array $match_objects array of MatchObject instances
     * @param int $limit limit to first n elements
     */
    function matchObjectToProductArrayMany(array $match_objects, int $limit = null){

        $output = [];

        if($limit){
            $match_objects = array_splice($match_objects, 0, $limit);
        }

        foreach($match_objects as $index => $match_object){
            $is_correct_type = $match_object instanceof MatchObject;
            if(!$is_correct_type){
                throw new MyException("Failed converting matchObjects to array: element $index not instanceof MatchObject", null, __FUNCTION__);
            }
        }

        foreach($match_objects as $match_object){
            $output[] = matchObjectToProductArray($match_object);
        }

        return $output;
    }