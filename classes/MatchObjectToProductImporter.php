<?php

    require_once(__DIR__."/../wp_init.php");
    require_once("abstract/AbstractImporter.php");
    require_once("MatchObjectToProductSettings.php");
    require_once("WooProductManagerLegacy.php");
    require_once(__DIR__."/../functions/wooGetProducts.php");
    require_once(__DIR__."/../functions/printRPre.php");

    class MatchObjectToProductImporter extends AbstractImporter {

        private IWooProductManager $product_manager;

        public function __construct(array $settings){

            $this->product_manager = new WooProductManagerLegacy();

            parent::__construct($settings);
        }

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

            //echo "Non existent product by sku: " . ($this->product_manager->productExistsBySku(-5) ? "true" : "false") . PHP_EOL;

            //Loop for each batch. Iterates as long as there is still data. Terminates if import count reaches $limit
            while(!empty($data) && $count_imported < $limit){

                //$product_json = wooGetProducts(["per_page" => 8]);

                foreach($data_portion as $entry_key => $entry){
                    $entry_sku = $entry->get("id");
                    $product = $this->product_manager->getProductBySku($entry_sku);
                    if($product){
                        //$product = $this->product_manager->getProduct($)
                        //Differences between data from MatchObject and data from product

                        $this->updateProductFromMatchObject($product["id"], $entry);

                        // $differences = $this->compareProductToMatchObject($product, $entry);
                        // echo $product->get_id() . " Product Exists. Differences: " . implode(", ", $differences) . PHP_EOL;
                        // if(in_array("description", $differences)){
                        //     echo $product->get_description() . PHP_EOL;
                        //     echo $entry->generateDescription() . PHP_EOL;
                        // }
                    }else{
                        $this->createProductFromMatchObject($entry);
                        echo "Doesn't exist" . PHP_EOL;
                    }
                    //$product_id = wc_get_product_id_by_sku($entry_sku);
                    //echo "$product_id" . PHP_EOL;
                    //$product = $this->product_manager->getProduct(["sku" => $entry_sku]);
                }

                //printRPre($product_json);

                $count_imported += $batch_size;
                //Get the next batch of data
                $data_portion = array_splice($data, 0, $batch_size);
            }

        }

        private function createProductFromMatchObject(MatchObject $match_object){
            $params = [
                "title" => $match_object->generateMatchTitle(),
                "sku" => $match_object->get("id"),
                "description" => $match_object->generateDescription(),
                "categories" => $match_object->generateCategoryIds()
            ];

            $this->product_manager->createProduct($params);
        }

        private function updateProductFromMatchObject($id, MatchObject $match_object){

            //$differences = $this->compareProductToMatchObject($id, $match_object);

            //echo "Updating Product $id differences: " . implode(", ", $differences) . PHP_EOL;
            $params = [
                "title" => $match_object->generateMatchTitle(),
                "sku" => $match_object->get("id"),
                "description" => $match_object->generateDescription(),
                "categories" => $match_object->generateCategoryIds()
            ];
            //$match_object->generateMetaInfo();
            //foreach($differences as $difference){
                //$params[$difference] = $match_object->get($difference);
            //}
            
            //if(!empty($params)){
                $this->product_manager->updateProduct($id, $params);
            //}

            
        }

        private function updateProductVariations($id, MatchObject $match_object){
            
        }

        private function compareProductToMatchObject($product_id, MatchObject $match_object){

            $product = $this->product_manager->getProduct($product_id);
            $match_object->generateMetaInfo();

            $differences = [];

            $exclude = ["id"];

            foreach($product as $key => $value){
                if($product[$key] !== $match_object->get($key) && !in_array($key, $exclude)){
                    $differences[] = $key;

                    //echo "Product has $key = $value" . PHP_EOL;
                    //echo "Object has $key = " . $match_object->get($key) . PHP_EOL;

                }
            }

            // if($product["title"] !== $match_object->generateMatchTitle()){
            //     $differences[] = "title";
            // }

            // if($product["description"] !== $match_object->generateDescription()){
            //     $differences[] = "description";
            // }

            // if($product["image_id"] !== $match_object->generateStadiumImageId()){
            //     $differences[] = "image_id";
            // }


            return $differences;
        }

        //private function setProductCategories()

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