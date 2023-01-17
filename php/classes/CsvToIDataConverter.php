<?php

    require_once("abstract/AbstractConverter.php");
    require_once("interface/IData.php");
    require_once("CsvToIDataSettings.php");

    class CsvToIDataConverter extends AbstractConverter {

        //protected array $required_settings = ["key_to_column_mappings", "data_object_prototype"];

        protected function convertAction($from){

            //Settings
            $settings = $this->getSettings();

            $data_object_prototype = $settings["data_object_prototype"];

            //Key>column mappings
            $column_mappings = $settings["key_to_column_mappings"];

            //File stream to read
            $file_handle = $from;

            //Output array. This is what will be returned
            $output_arr = [];

            //Current csv row parsed
            $row = 1;

            $skip_first_n_rows = 1;
            while(($data = fgetcsv($file_handle, 10000, ",")) !== FALSE){
                $num = count($data);

                if($row > $skip_first_n_rows){

                    //Map key=>column pairs to a raw assoc_array
                    $entry_arr = [];
                    foreach($column_mappings as $key => $column_number){
                        $entry_arr[$key] = $data[$column_number];
                    }
                    // $match["home_club"] = $data[0];
                    // $match["away_club"] = $data[1];
                    // $match["stadium"] = $data[2];
                    // $match["tournament"] = $data[3];
                    // $match["match_date"] = $data[4];
                    // $match["match_time"] = $data[5];
                    // $match["match_fixed"] = $data[6];
                    // $match["ticket_only_discount"] = $data[7];
                    // $match["cat_1_qty"] = $data[8];
                    // $match["cat_1_price"] = $data[9];
                    // $match["cat_2_qty"] = $data[10];
                    // $match["cat_2_price"] = $data[11];
                    // $match["cat_3_qty"] = $data[12];
                    // $match["cat_3_price"] = $data[13];
                    // $match["cat_4_qty"] = $data[14];
                    // $match["cat_4_price"] = $data[15];
                    // $match["hotel_price"] = $data[16];
                    // $match["package"] = $data[17];
                    // $match["description"] = $data[18];
                    // $match["available_before_days"] = $data[19];
                    // $match["id"] = $data[20];

                    //Convert the raw $entry_arr to IData
                    $entry = clone $data_object_prototype;
                    foreach($entry_arr as $key => $value){
                        $entry->set($key, $value);
                    }
                    
                    //Push newly created IData to output array
                    $output_arr[$row - ($skip_first_n_rows + 1)] = $entry;
                }

                $row++;
                // for($i = 0; $i < $num; $i++){
                    
                // }
                
            }

            fclose($file_handle);

            return $output_arr;
        }

        protected function generateSettingsObject(){
            return new CsvToIDataSettings();
        }

    }