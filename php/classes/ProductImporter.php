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
            // foreach($data as $row){
            //     //echo "hello";
            // }
        }

        /**
         * Main import action called by the import() method
         * 
         * @param array $data data to import
         */
        protected function importAction(array $data, ...$args){
            
            

            //Settings
            $settings = $this->getSettings();
            
            $session = $settings["session"];
            //Amount to import per batch
            $batch_size = $settings["batch_size"];
            //Limit import to first $limit results
            $limit = $settings["limit"];
            //Variable by which to import
            $importBy = $settings["importBy"];

            $start_time = $args[0];

            

            $logger = $this->logger;

            $logger->log("Start time: " . strval($start_time));

            //Remove elements that are above limit count
            $data = array_slice($data, 0, $limit);

            if($importBy !== "sku"){
                throw new MyException("Invalid importBy setting specified", $this, __METHOD__);
            }

            $data_count = count($data);

            //Loop data
            //Amount of items imported
            $count_imported = 0;

            $batch_count = ceil($data_count / $batch_size);
            $batch_index = 0;

            $total_updated = 0;
            $total_created = 0;
            $total_deleted = 0;

            $logger->log("Will import $data_count products");
            $logger->log("");

            //$start_time = time();

            $session->set(
                EnumSessionDataElement::ProgressData,
                [
                    "message" => "Starting import",
                    "new" => false,
                    "started" => true,
                    "finished" => false,
                    "start_time" => $start_time,
                    "end_time" => time(),
                    "error" => false,
                    "error_message" => ""
                ]
            );
            
            //Loop for each batch. Iterates as long as there is still data. Terminates if import count reaches $limit
            while(!empty($data) && $count_imported < $limit){

                $batch_index += 1;

                //Single batch of data to import
                $data_portion = array_splice($data, 0, $batch_size);

                $logger->log("-------- Batch $batch_index/$batch_count");
                $session->set(
                    EnumSessionDataElement::ProgressData,
                    [
                        "message" => "Importing Batch $batch_index/$batch_count",
                        "new" => false,
                        "started" => true,
                        "finished" => false,
                        "start_time" => $start_time,
                        "end_time" => time(),
                        "error" => false,
                        "error_message" => ""
                    ]
                );


                //Determine which entries must be updated and which must be created
                $entries_to_create = [];
                $entries_to_update = [];
                $entries_to_delete = [];
                foreach($data_portion as $entry_key => $entry){
                    //Whether the entry should not be created/should be deleted
                    $delete_entry = !$entry["enable"];
                    
                    $product = null;
                    if($importBy === "sku"){
                        //echo "sku: " . $entry["sku"];
                        $sku = $entry["sku"];
                        $product = $this->product_manager->getProductBySku($sku);
                    }

                    if($product){
                        //printRPre($product);
                        $entry["id"] = $product["id"];
                        if(!$delete_entry){
                            $entries_to_update[] = $entry;
                        }else{
                            $entries_to_delete[] = $product["id"];
                        }
                    }else if(!$delete_entry){
                        $entries_to_create[] = $entry;
                    }
                }

                $response = $this->product_manager->bulkUpdateProducts(
                    $entries_to_create,
                    $entries_to_update,
                    $entries_to_delete
                );

                // echo "\nresponse: \n";
                // printRPre($response);

                // $entries_updated_count = isset($response["update"]) ? count($response["update"]) : null;
                // $entries_created_count = isset($response["create"]) ? count($response["create"]) : null;
                // $entries_deleted_count = isset($response["delete"]) ? count($response["delete"]) : null;

                

                $this->logCount("create", "Created ", $total_created, $response, $logger);
                $this->logCount("update", "Updated ", $total_updated, $response, $logger);
                $this->logCount("delete", "Deleted ", $total_deleted, $response, $logger);

                

                // if($entries_created_count){
                //     $logger->log("Created $entries_created_count products");
                //     $total_created += $entries_created_count;
                // }
                // if($entries_updated_count){
                //     $logger->log("Updated $entries_updated_count products");
                //     $total_updated += $entries_created_count;
                // }
                // if($entries_deleted_count){
                //     $logger->log("Deleted $entries_updated_count products");
                // }

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

                $count_imported += $batch_size;
            //     //$batch_size = $this->calcBatchSize($count_imported, $limit, $batch_size);
            //     //Get the next batch of data
            //     //$data_portion = array_splice($data, 0, $batch_size);
            }
            $session->set(
                EnumSessionDataElement::ProgressData,
                [
                    "message" => "Import completed!",
                    "new" => false,
                    "started" => false,
                    "finished" => true,
                    "start_time" => $start_time,
                    "end_time" => time(),
                    "error" => false,
                    "error_message" => ""
                ]
            );
            $logger->log("-------- Importing has finished successfully!");
            $logger->log("Total created: $total_created");
            $logger->log("Total updated: $total_updated");
            $logger->log("Total deleted: $total_deleted");

            
        }

        // private function updateProduct(){

        // }

        /**
         * Log count information if response's element of specified key is set
         * 
         * @param string $key key to check
         * @param string $prefix prefix of log
         * @param int &$total variable that stores total number of entries for specific action
         * @param array $response response of product_manager->bulkUpdateProducts()
         * @param ILogger $logger to use
         * 
         * @return void
         */
        private function logCount(string $key, string $prefix, int &$total, array $response, ILogger $logger){

            $count = isset($response[$key]) ? count($response[$key]) : null;
            //echo "count: " . var_dump($count);
            if($count){
                $total += $count;
                $ids = [];
                foreach($response[$key] as $product){
                    $ids[] = $product["id"];
                }
                $logger->log($prefix . $count . " Products: " . implode(", ", $ids));
            }
        }

        protected function generateSettingsObject(){
            return new ProductImporterSettings();
        }

    }