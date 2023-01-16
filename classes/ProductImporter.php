<?php

    require_once("abstract/AbstractImporter.php");
    require_once("ProductImporterSettings.php");
    require_once("WooProductManagerRest.php");

    class ProductImporter extends AbstractImporter {


        private IWooProductManager $product_manager;

        private ILogger $logger;

        public function __construct(array $settings){

            parent::__construct($settings);

            //$this->product_manager = new WooProductManagerLegacy($settings["logger"]);
            $this->product_manager = new WooProductManagerRest($settings["logger"]);

            $this->logger = $settings["logger"];
        }

        protected function validateAction(array $data){
            
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
            //Variable by which to import
            $importBy = $settings["importBy"];

            //Remove elements that are above limit count
            $data = array_slice($data, 0, $limit);

            if($importBy !== "sku"){
                throw new MyException("Invalid importBy setting specified", $this, __METHOD__);
            }


            //Loop data
            //Amount of items imported
            $count_imported = 0;

            //Loop for each batch. Iterates as long as there is still data. Terminates if import count reaches $limit
            while(!empty($data) && $count_imported < $limit){

                //Single batch of data to import
                $data_portion = array_splice($data, 0, $batch_size);


                //Determine which entries must be updated and which must be created
                $entries_to_create = [];
                $entries_to_update = [];
                foreach($data_portion as $entry_key => $entry){
                    $product = null;
                    if($importBy === "sku"){
                        //echo "sku: " . $entry["sku"];
                        $sku = $entry["sku"];
                        $product = $this->product_manager->getProductBySku($sku);
                    }

                    if($product){
                        //printRPre($product);
                        $entry["id"] = $product["id"];
                        $entries_to_update[] = $entry;
                    }else{
                        $entries_to_create[] = $entry;
                    }
                }

                $this->product_manager->bulkUpdateProducts($entries_to_update);

                //Iterate through batch
            //     foreach($data_portion as $entry_key => $entry){
            //         $product = null;
            //         if($importBy === "sku"){
            //             $sku = $entry["sku"];
            //             $product = $this->product_manager->getProductBySku($sku);
            //         }
            //         $session_message = "";
            //         if($product){
            //             $product_id = $product["id"];
            //         }
            //         if($product){
            //             //$product = $this->product_manager->getProduct($)
            //             //Differences between data from MatchObject and data from product
            //             $this->logger->log("Updating " . $product["title"]);
            //             $this->product_manager->updateProduct($product_id, $entry);

            //             $session_message = "Updated product " . $entry["title"] . PHP_EOL;
            //         }else{
            //             $this->logger->log("Creating " . $entry["title"]);

            //             $session_message = "Created product " . $entry["title"] . PHP_EOL;
            //         }
                    

            //         $start_time = $session->get(EnumSessionDataElement::ProgressData)["start_time"];

            //         $session->set(
            //             EnumSessionDataElement::ProgressData,
            //             [
            //                 "index" => $count_imported + $entry_key,
            //                 "title" => $session_message,
            //                 "message" => $session_message,
            //                 "new" => false,
            //                 "started" => true,
            //                 "finished" => false,
            //                 "start_time" => $start_time,
            //                 "end_time" => time(),
            //                 "error" => false,
            //                 "error_message" => ""
            //             ]
            //         );
            //         //$product_id = wc_get_product_id_by_sku($entry_sku);
            //         //echo "$product_id" . PHP_EOL;
            //         //$product = $this->product_manager->getProduct(["sku" => $entry_sku]);
            //     }

            //     //printRPre($product_json);

            //     $count_imported += $batch_size;
            //     //$batch_size = $this->calcBatchSize($count_imported, $limit, $batch_size);
            //     //Get the next batch of data
            //     //$data_portion = array_splice($data, 0, $batch_size);
            }

        }

        // private function updateProduct(){

        // }

        protected function generateSettingsObject(){
            return new ProductImporterSettings();
        }

    }