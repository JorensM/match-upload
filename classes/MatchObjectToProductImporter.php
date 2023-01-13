<?php

    require_once(__DIR__."/../wp_init.php");
    require_once("abstract/AbstractImporter.php");
    require_once("MatchObjectToProductSettings.php");
    require_once("WooProductManagerLegacy.php");
    require_once(__DIR__."/../functions/wooGetProducts.php");
    require_once(__DIR__."/../functions/printRPre.php");
    require_once("enum/EnumSessionDataElement.php");

    class MatchObjectToProductImporter extends AbstractImporter {

        private IWooProductManager $product_manager;

        private ILogger $logger;

        public function __construct(array $settings){

            $this->product_manager = new WooProductManagerLegacy();

            parent::__construct($settings);

            $this->logger = $settings["logger"];
        }

        private function calcBatchSize($count_imported, $limit, $batch_size){
            if($count_imported + $batch_size > $limit){
                return ($count_imported + $batch_size) - $limit;
            }else{
                return $batch_size;
            }
        }

        /**
         * Main import action called by the import() method
         * 
         * @param array $data data to import
         */
        protected function importAction(array $data){
            
            

            //Settings
            $settings = $this->getSettings();
            
            $session = $settings["session"];
            //Amount to import per batch
            $batch_size = $settings["batch_size"];
            //Limit import to first $limit results
            $limit = $settings["limit"];

            //Remove elements that are above limit count
            $data = array_slice($data, 0, $limit);

            


            //Loop data
            //Amount of items imported
            $count_imported = 0;

            //$batch_size = $this->calcBatchSize($count_imported, $limit, $batch_size);

            

            //echo "\n data portion:  \n" . count($data_portion); 
            //printRPre($data_portion);

            //echo "Non existent product by sku: " . ($this->product_manager->productExistsBySku(-5) ? "true" : "false") . PHP_EOL;

            //Loop for each batch. Iterates as long as there is still data. Terminates if import count reaches $limit
            while(!empty($data) && $count_imported < $limit){

                //Single batch of data to import
                $data_portion = array_splice($data, 0, $batch_size);

                //$product_json = wooGetProducts(["per_page" => 8]);
                echo "size: " . count($data_portion);
                foreach($data_portion as $entry_key => $entry){
                    echo "\n loop";
                    $entry_sku = $entry->get("id");
                    $product = $this->product_manager->getProductBySku($entry_sku);
                    $session_message = "";
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
                        $session_message = "Updated product " . $entry->generateMatchTitle() . PHP_EOL;
                    }else{
                        echoNl("Creating product");
                        $this->createProductFromMatchObject($entry);
                        //echo "Doesn't exist" . PHP_EOL;
                        $session_message = "Created product " . $entry->generateMatchTitle() . PHP_EOL;
                    }

                    $start_time = $session->get(EnumSessionDataElement::ProgressData)["start_time"];

                    $session->set(
                        EnumSessionDataElement::ProgressData,
                        [
                            "index" => $count_imported + $entry_key,
                            "title" => $session_message,
                            "message" => $session_message,
                            "new" => false,
                            "started" => true,
                            "finished" => false,
                            "start_time" => $start_time,
                            "end_time" => time(),
                            "error" => false,
                            "error_message" => ""
                        ]
                    );
                    //$product_id = wc_get_product_id_by_sku($entry_sku);
                    //echo "$product_id" . PHP_EOL;
                    //$product = $this->product_manager->getProduct(["sku" => $entry_sku]);
                }

                //printRPre($product_json);

                $count_imported += $batch_size;
                //$batch_size = $this->calcBatchSize($count_imported, $limit, $batch_size);
                //Get the next batch of data
                //$data_portion = array_splice($data, 0, $batch_size);
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
            $this->updateProductVariations($id, $match_object);

            
        }

        private function updateProductVariations($product_id, MatchObject $match_object){
            $variation_data = $match_object->generateVariationData();

            //Variations that will be removed.
            $variations_to_remove = [];

            //The loop will set $variation_to_remove entry to false if it loops over that variation
            foreach($variation_data as $variation){

                //echo "\n variation enable: " . var_dump($variation["enable"]) . "\n";

                if($variation["enable"]){
                    $this->product_manager->updateProductVariation(
                        $product_id,
                        $variation["name"], 
                        [
                            "regular_price" => $variation["regular_price"],
                            "manage_stock" => false
                        ]
                    );
                }else{
                    $variations_to_remove[] = $variation;
                }
            }
            foreach($variations_to_remove as $variation){
                $variation_exists = $this->product_manager->getVariationByName($product_id, $variation["name"]);
                if($variation_exists){
                    $this->product_manager->removeProductVariation($product_id, $variation["name"]);
                }
                
            }
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