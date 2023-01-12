<?php

    require_once("abstract/AbstractImporter.php");
    require_once("MatchObjectToProductSettings.php");
    require_once(__DIR__."/../functions/wooGetProducts.php");

    class MatchObjectToProductImporter extends AbstractImporter {

        /**
         * Main import action called by the import() method
         * 
         * @param array $data data to import
         */
        protected function importAction(array $data){
            
            //Settings
            $settings = $this->getSettings();
            
            //Amount to import per batch
            $batch_size = $settings["batch_size"];
            //Limit import to first $limit results
            $limit = $settings["limit"];

            //Loop data
            //Amount of items imported
            $count_imported = 0;

            //Single batch of data to import
            $data_portion = array_splice($data, 0, $batch_size);

            //Loop for each batch. Iterates as long as there is still data. Terminates if import count reaches $limit
            while(!empty($data) && $count_imported < $limit){

                //$product_json = wooGetProducts(["sku" => "13"]);
                //printRPre($product_json);

                $count_imported += $batch_size;
                //Get the next batch of data
                $data_portion = array_splice($data, 0, $batch_size);
            }

        }

        /**
         * Validator action called in import() method before importAction().
         * Throws error on validation fail.
         * 
         * @param array $data data to be imported
         * 
         * @return bool true on success.
         */
        protected function validateAction(array $data){

            //Keys of the $data array's elements that are invalid
            $invalid_keys = [];

            foreach($data as $key => $value){

                $is_valid = $this->checkSingle($value);

                if(!$is_valid){
                    array_push($invalid_keys, $key);
                }
            }

            if(!empty($invalid_keys)){
                $e_message = "Data validation failed, array elements with the following keys are invalid: " . implode(", ", $invalid_keys);
                throw new MyException($e_message, $this, __METHOD__);
            }

            return true;
        }


        /**
         * Check if a single data entry is valid. Used in validateAction() method
         * 
         * @param any $data_entry single data entry
         * 
         * @return bool true if validation passed, false otherwise
         */
        private function checkSingle($data_entry){

            //Whether entry is of correct class
            $is_correct_class = $data_entry instanceof MatchObject;
            
            if(!$is_correct_class){
                return false;
            }else{
                return true;
            }
        }

        protected function generateSettingsObject(){
            return new MatchObjectToProductSettings();
        }

    }