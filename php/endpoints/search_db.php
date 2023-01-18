<?php

    //Core
    require_once(__DIR__."/../../wp_init.php");

    //Functions
    require_once(__DIR__."/../functions/printRPre.php");

    global $wpdb;

    //Get all table names from DB
    $query = "SELECT table_name FROM information_schema.tables;";
    $result = $wpdb->get_results($query, OBJECT);

    //$tables = [];

    //Search value
    $search_value = $_POST["search_value"];

    //Loop through each table name
    foreach($result as $table){
        echo $table->table_name . "<br>";
        //Extract table name into a variable
        $name = $table->table_name;

        //Get column information for the table
        $query = "DESCRIBE $name;";
        $columns_result = $wpdb->get_results($query, OBJECT);

        //Columns that will be checked
        $columns = [];

        //Get columns that are of type "text" or "char"
        if(count($columns_result) > 0 ){
            //Loop through columns if there are any
            foreach($columns_result as $column){
                $type = $column->Type;
                //Check if column is of type "text" or "char", and if true, 
                //add it to the columns-to-check array
                if(str_contains($type, "text") || str_contains($type, "char")){
                    array_push($columns, $column->Field);
                }
                
            }
        }else{
            continue;
        }

        //Search filtered columns for the search term
        $query = "SELECT * FROM $name WHERE '$search_value' IN (" . implode(',', $columns) . ") ;";
        echo $query . "<br>";
        $final_result = $wpdb->get_results($query, OBJECT);
        
        
        //Output final result
        $final_array = [];
        foreach($final_result as $final){
            if(count($final) > 1){
                array_push($final_array, $final);
            }
        }

        printRPre($final_array);
        
    }
