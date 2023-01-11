<?php

    require_once(__DIR__."/../interface/IData.php");

    // abstract class AbstractData implements IData{
    //     private array $data;

    //     private IDataEntry $entry_prototype;

    //     public function entry($key){

    //         $is_entry_set = isset($this->data[$key]);

    //         if(!$is_entry_set){
    //             //return $this->data["key"];
    //             $this->data[$key] = clone $this->entry_prototype;
    //         }

    //         return $this->data[$key];
            
    //     }

    //     public function allEntries(){
    //         return $this->data;
    //     }

    //     public function __construct(IDataEntry $entry_prototype){

    //         $this->entry_prototype = $entry_prototype;

    //         $this->data = [];
    //     }
    // }