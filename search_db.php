<?php

    require_once("wp_init.php");

    global $wpdb;

    //$wpdb->show_errors();
    //$wpdb->print_error();

    $query = "SELECT table_name FROM information_schema.tables;";
    $result = $wpdb->get_results($query, OBJECT);

    $tables = [];

    $search_value = $_POST["search_value"];

    foreach($result as $table){
        echo $table->table_name . "<br>";
        $name = $table->table_name;

        $query = "DESCRIBE $name;";
        $columns_result = $wpdb->get_results($query, OBJECT);

        $columns = [];

        if(count($columns_result) > 0 ){
            foreach($columns_result as $column){
                $type = $column->Type;
                if(str_contains($type, "text") || str_contains($type, "char")){
                    array_push($columns, $column->Field);
                }
                
            }
        }else{
            continue;
        }

        $query = "SELECT * FROM $name WHERE '$search_value' IN (" . implode(',', $columns) . ") ;";
        echo $query . "<br>";
        $final_result = $wpdb->get_results($query, OBJECT);
        
        

        $final_array = [];
        foreach($final_result as $final){
            if(count($final) > 1){
                array_push($final_array, $final);
            }
        }

        echo "<pre>";
        echo print_r($final_array);
        echo "</pre>";
        

        //$tables[$name] = [];
    }
