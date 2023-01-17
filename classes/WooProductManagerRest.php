<?php

    require_once(__DIR__."/../wp_init.php");

    require_once("interface/IWooProductManager.php");
    require_once("interface/ILogger.php");

    require_once(__DIR__."/../functions/printRPre.php");
    require_once(__DIR__."/../functions/wooGetProducts.php");
    require_once(__DIR__."/../functions/wooUpdateProducts.php");
    require_once(__DIR__."/../functions/wooUpdateVariations.php");
    require_once(__DIR__."/../functions/wooGetVariations.php");
    

    class WooProductManagerRest implements IWooProductManager{

        private ILogger $logger;

        public function __construct(ILogger $logger){
            $this->logger = $logger;
        }

        public function getProduct($id){
            $product = new WC_Product_Variable($id);

            return $this->assocArrayFromProduct($product);
        }

        public function getProductBySku($sku){
            $products = wooGetProducts(["sku" => $sku]);

            //printRPre($product[0]);
            if(isset($products[0])){
                return $products[0];
            }
            //if($id > 0){
                //$product = new WC_Product_Variable($id);
                //return $this->assocArrayFromProduct($product);
            //}
            return null;

        }

        /**
         * Bulk update products. Possible to create, update and delete products
         * 
         * @param array $products_to_create products that will be created
         * @param array $products_to_update products that will be updated
         * @param array $products_to_delete product ids that will be deleted
         * 
         * @return void
         */
        public function bulkUpdateProducts(array $products_to_create, array $products_to_update, array $products_to_delete){

            //Products converted to the REST API's format
            $products_to_create_rest_format = $this->productArrayToRestMultiple($products_to_create);
            $products_to_update_rest_format = $this->productArrayToRestMultiple($products_to_update);

            

            // foreach($products as $product){
                
            //     //Convert category ids to REST API's supported category format
            //     if($product["category_ids"]){
            //         //$products_rest_format["categories"] = [];
            //         foreach($product["category_ids"] as $category_id){
            //             $product["categories"][] = [
            //                 "id" => $category_id
            //             ];
            //         }
            //         unset($product["category_ids"]);
            //     }
            //     //echo "product metadata: ";
            //     //printRPre($product["meta_data"]);
            //     //unset($product["meta_data"]);
            //     unset($product["image_id"]);
            //     unset($product["variations"]);
            //     $products_rest_format[] = $product;
            // }

            //echo "\nrequest:\n";
            //printRPre(json_encode($products_rest_format));

            $response = wooUpdateProducts(
                $products_to_create_rest_format,
                $products_to_update_rest_format,
                $products_to_delete
            );

            // echo "\nupdated products: \n";
            // echo "<pre>";
            // print_r(json_encode($response));
            // echo "</pre>";

            // echo "\n products: \n";
            // printRPre($products);

            //Update variations
            foreach($products_to_update as $product){
                //echo "\nlooping\n";
                
                $this->updateVariations($product);
                //$response = wooUpdateVariations($product["id"],);
            }

            foreach($products_to_create as $product){
                $this->updateVariations($product);
            }
            //printRPre($response);
            return $response;
        }

        /**
         * Convert a product array into an array supported by the REST API
         * 
         * @param array $product product array to convert
         * 
         * @return array REST API supported product
         */
        private function productArrayToRest(array $product){
            //Convert category ids to REST API's supported category format
            if($product["category_ids"]){
                //$products_rest_format["categories"] = [];
                foreach($product["category_ids"] as $category_id){
                    $product["categories"][] = [
                        "id" => $category_id
                    ];
                }
                unset($product["category_ids"]);
            }
            //echo "product metadata: ";
            //printRPre($product["meta_data"]);
            //unset($product["meta_data"]);
            unset($product["image_id"]);
            unset($product["variations"]);
            return $product;
        }

        /**
         * Calls productArrayToRest multiple times
         * 
         * @param array $products products to convert
         * 
         * @return array REST API supported products
         */
        private function productArrayToRestMultiple(array $products){
            $output = [];
            foreach($products as $product){
                $output[] = $this->productArrayToRest($product);
            }
            return $output;
        }

        private function updateVariations($product){
            //echo "Updating variations for: " . $product["title"] . "\n";
            $all_variations = wooGetVariations($product["id"]);//$this->getVariationByDescription()

            //echo "a";
            //Variation converted to REST API format
            $variations_to_delete = [];
            $variations_to_update_rest_format = [];
            $variations_to_create_rest_format = [];

            foreach($all_variations as $index => $variation){
                $all_variations[$index]["description"] = wp_strip_all_tags($variation["description"]);
                $variation["description"] = wp_strip_all_tags($variation["description"]);
                if($variation["description"] === null || $variation["description"] === ""){
                    $variations_to_delete[] = $variation["id"];
                }
            }
            //echo "b";

            $category_attribute_id = 1;
            $category_attribute_name = "Seat Category";

            foreach($product["variations"] as $variation){
                //echo "\n current variation: \n";
                //printRPre($variation);
                $single_variation_to_update_id = $this->getVariationByDescription($all_variations, $variation["description"]);

                if(!$variation["enable"] && $single_variation_to_update_id){
                    $variations_to_delete[] = $single_variation_to_update_id;
                    //continue;
                }

                if(!$variation["enable"]){
                    continue;
                }

                if($single_variation_to_update_id){
                    //echo "\n found id! " . $single_variation_to_update_id . "\n";
                    $variations_to_update_rest_format[] = [
                        "id" => $single_variation_to_update_id,
                        "regular_price" => $variation["regular_price"],
                        "description" => $variation["description"],
                        "attributes" => [
                            [
                                //"id" => $category_attribute_id,
                                "name" => $category_attribute_name,
                                "option" => $variation["description"]
                            ]
                        ]
                    ];
                }else{
                    $variations_to_create_rest_format[] = [
                        "regular_price" => $variation["regular_price"],
                        "description" => $variation["description"],
                        "attributes" => [
                            [
                                //"id" => $category_attribute_id,
                                "name" => $category_attribute_name,
                                "option" => $variation["description"]
                            ]
                        ]
                    ];
                }
            }
            // echo "c";
            // echo "\nto create: \n";
            // printRPre($variations_to_create_rest_format);
            // echo "\nto update: \n";
            // printRPre($variations_to_update_rest_format);
            // echo "\nto delete: \n";
            // printRPre($variations_to_delete);
            $res = wooUpdateVariations(
                $product["id"],
                $variations_to_create_rest_format,
                $variations_to_update_rest_format,
                $variations_to_delete
            );
            // printRPre($res);
            // echo "d";
        }

        private function getVariationByDescription($all_variations, $variation_description){
            foreach($all_variations as $variation){

                $stripped = wp_strip_all_tags($variation["description"]);//strip_tags($variation["description"]);


                // echo "\nComparing: \n";
                // echo "\n". $stripped . "\n" . gettype($stripped);
                // echo "\n$variation_description\n" . gettype($variation_description);
                // echo var_dump($stripped);
                // echo var_dump($variation_description);

                if(strcmp($stripped, $variation_description) === 0){//strip_tags($variation["description"]) == $variation_description){
                    //echo "\nFound matching variation! " . $variation["id"] . "\n";
                    return $variation["id"];
                }
            }
            return null;
        }

        // private function updateProductVariations($product_id, array $variations){

        // }

        private function assocArrayFromProduct(WC_Product_Variable $product){
            return [
                "id" => $product->get_id(),
                "title" => $product->get_title(),
                "description" => $product->get_description()
            ];
        }

        public function productExistsBySku($sku){
            
        }

        public function createProduct(array $params){
            
        }

        public function updateProduct($id, array $params){
            
        }

        public function getVariationByName($product_id, string $variation_name){
            
        }

        public function updateProductVariation($product_id, $variation_name, array $params){
            
        }

        public function removeProductVariation($product_id, $variation_name){
            
        }

        // public function createProduct(array $params){

        //     //echo "Creating product: " . PHP_EOL;
        //     $product = new WC_Product_Variable();

        //     //printRPre($product);
        // }

        /**
         * Extract what is different in array 1 from array 2
         * 
         * @param array $product_1 product 1
         * @param array $product_2 product 2
         * 
         * @return array array of params that are different
         */
        // private function extractDifferencesInProuductArrays(array $product_1, array $product_2){
        //     $output = [];

        //     function checkAndAdd(array $keys){
        //         global $product_1;
        //         global $product_2;
        //         foreach($keys as $key){
        //             if($product_1[$key] !== $product_2[$key]){
        //                 $output[$key] = $product_2[$key];
        //             }
        //         }
        //     }

        //     function checkAndAddMetadata(){
        //         global $product_1;
        //         global $product_2;

        //         if(!isset($product_2["meta_data"])){
        //             return;
        //         }
        //         if(!isset($product_1["meta_data"])){
        //             $output["meta_data"] = $product_2["meta_data"];
        //         }

        //         foreach($product_2["meta_data"] as $key => $entry){
        //             //$p_1_field;
        //             //$p_2_field;
        //             if(
        //                 !isset($product_1["meta_data"][$key]) || 
        //                 $product_1["meta_data"][$key] !== $product_2["meta_data"][$key]
        //             ){

        //             }
        //         }

        //         //foreach($keys as $key){
        //             //if($product_1["meta_data"][$key] !== $product_2["meta_data"][$key]){
        //                 //$output["meta_data"][$key] = $product_2[$key];
        //             //}
        //         //}
        //     }

        //     function checkAndAddVariations(){
        //         global $product_1;
        //         global $product_2;

        //         if(!isset($product_2["va"]))

        //     }

        //     checkAndAdd([
        //         "title",
        //         "description",
        //         "category_ids",
        //         "image_id",
        //         "sku"
        //     ]);

            //checkAndAddMetadata()
        //}

        // public function updateProduct($id, array $params){
        //     $product = new WC_Product_Variable($id);

        //     //$old_product_arr = $this->assocArrayFromProduct($product);
        //     //$new_product_arr = $this->extractDifferencesInProuductArrays($product);

        //     $name = array_key_exists("title", $params) ? $params["title"] : $product->get_title();
        //     $description = array_key_exists("description", $params) ? $params["description"] : $product->get_description();
        //     $categories = array_key_exists("category_ids", $params) ? $params["category_ids"] : $product->get_category_ids();
        //     $image_id = array_key_exists("image_id", $params) ? $params["image_id"] : $product->get_image_id();
            

        //     //$variations = $product->get_available_variations();

        //     //printRPre($variations);

        //     $product->set_name($name);
        //     $product->set_description($description);
        //     $product->set_category_ids($categories);
        //     $product->set_image_id($image_id);

        //     foreach($params["variations"] as $variation){
        //         if($variation["enable"]){
        //             $this->updateProductVariation($id, $variation["name"], $variation);
        //         }else{
        //             try{
        //                 $this->removeProductVariation($id, $variation["name"]);
        //             }catch(Exception $e){

        //             }
                    
        //         }
        //     }

        //     $product->save();
        // }

        // public function updateProductVariation($product_id, $variation_name, array $params){

        //     $product = new WC_Product_Variable($product_id);


        //     if(!$product){
        //         throw new MyException("Couldn't update variation for product $product_id - product not found", $this, __METHOD__);
        //     }

        //     $variation = $this->getVariationObjByName($product, $variation_name);

        //     if(!$variation){
        //         throw new MyException("Couldn't update variation $variation_name for product $product_id - variation not found", $this, __METHOD__);
        //     }

        //     $price = array_key_exists("regular_price", $params) ? $params["regular_price"] : $variation->get_regular_price();
        //     $manage_stock = array_key_exists("manage_stock", $params) ? $params["manage_stock"] : $variation->get_manage_stock();

        //     $variation->set_regular_price($price);
        //     $variation->set_manage_stock($manage_stock);

        //     $variation->save();

        // }

        // private function getVariationObjByName(WC_Product_Variable $product, $variation_name){
        //     $variation_ids = $product->get_children();//$product->get_available_variations("objects");

        //     $variations = [];

        //     foreach($variation_ids as $variation_id){
        //         $variations[] = new WC_Product_Variation($variation_id);
        //     }
            
        //     foreach($variations as $variation){
        //         if($variation->get_name() === $variation_name){
        //             return $variation;
        //         }
        //     }
        //     return null;
        // }

        // public function updateOrCreateProductVariation($product_id, $variation_name, array $params){
        //     $product = new WC_Product_Variable($product_id);

        //     if(!$product){
        //         throw new MyException("Couldn't update variation for product $product_id - product not found", $this, __METHOD__);
        //     }

        //     echo "d";
        //     $variation = $this->getVariationObjByName($product, $variation_name);

        //     if(!$variation){
        //         //$this->createVa
        //     }else{
                
        //     }

        // }

        // public function removeProductVariation($product_id, $variation_name){
        //     //$this->logger->log("Deleting variation");

        //     $product = new WC_Product_Variable($product_id);

        //     $variation = $this->getVariationObjByName($product, $variation_name);
        //     if($variation){
        //         $variation->delete();
        //     }else{
        //         //error_log()
        //         throw new MyException("Could not find variation '$variation_name' in Product $product_id", $this, __METHOD__);
        //     }
            
        // }

        // public function getVariationByName($product_id, $variation_name){
        //     $product = new WC_Product_Variable($product_id);
        //     $variations = $product->get_available_variations();
        //     foreach($variations as $variation){
        //         if($variation["name"] === $variation_name){
        //             return $variation;
        //         }
        //     }
        //     return null;
        // }

        // private function getProductVariations($product_id){
        //     $product = new WC_Product_Variable($product_id);

        //     return $product->get_available_variations();
        // }

    }