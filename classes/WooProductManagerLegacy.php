<?php

    require_once(__DIR__."/../wp_init.php");

    require_once("interface/IWooProductManager.php");
    require_once("interface/ILogger.php");

    require_once(__DIR__."/../functions/printRPre.php");
    

    class WooProductManagerLegacy implements IWooProductManager{

        private ILogger $logger;

        public function __construct(ILogger $logger){
            $this->logger = $logger;
        }

        public function getProduct($id){
            $product = new WC_Product_Variable($id);

            return $this->assocArrayFromProduct($product);
        }

        public function getProductBySku($sku){
            $id = wc_get_product_id_by_sku($sku);

            if($id > 0){
                $product = new WC_Product_Variable($id);
                return $this->assocArrayFromProduct($product);
            }
            return null;

        }

        private function assocArrayFromProduct(WC_Product_Variable $product){
            return [
                "id" => $product->get_id(),
                "title" => $product->get_title(),
                "description" => $product->get_description()
            ];
        }

        public function productExistsBySku($sku){
            $exists = wc_get_product_id_by_sku($sku) > 0;
            return $exists;
        }

        public function createProduct(array $params){

            //echo "Creating product: " . PHP_EOL;
            $product = new WC_Product_Variable();

            //printRPre($product);
        }

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

        public function updateProduct($id, array $params){
            $product = new WC_Product_Variable($id);

            //$old_product_arr = $this->assocArrayFromProduct($product);
            //$new_product_arr = $this->extractDifferencesInProuductArrays($product);

            $name = array_key_exists("title", $params) ? $params["title"] : $product->get_title();
            $description = array_key_exists("description", $params) ? $params["description"] : $product->get_description();
            $categories = array_key_exists("category_ids", $params) ? $params["category_ids"] : $product->get_category_ids();
            $image_id = array_key_exists("image_id", $params) ? $params["image_id"] : $product->get_image_id();
            

            //$variations = $product->get_available_variations();

            //printRPre($variations);

            $product->set_name($name);
            $product->set_description($description);
            $product->set_category_ids($categories);
            $product->set_image_id($image_id);

            foreach($params["variations"] as $variation){
                if($variation["enable"]){
                    $this->updateProductVariation($id, $variation["name"], $variation);
                }else{
                    try{
                        $this->removeProductVariation($id, $variation["name"]);
                    }catch(Exception $e){

                    }
                    
                }
            }

            $product->save();
        }

        public function updateProductVariation($product_id, $variation_name, array $params){

            $product = new WC_Product_Variable($product_id);


            if(!$product){
                throw new MyException("Couldn't update variation for product $product_id - product not found", $this, __METHOD__);
            }

            $variation = $this->getVariationObjByName($product, $variation_name);

            if(!$variation){
                throw new MyException("Couldn't update variation $variation_name for product $product_id - variation not found", $this, __METHOD__);
            }

            $price = array_key_exists("regular_price", $params) ? $params["regular_price"] : $variation->get_regular_price();
            $manage_stock = array_key_exists("manage_stock", $params) ? $params["manage_stock"] : $variation->get_manage_stock();

            $variation->set_regular_price($price);
            $variation->set_manage_stock($manage_stock);

            $variation->save();

        }

        private function getVariationObjByName(WC_Product_Variable $product, $variation_name){
            $variation_ids = $product->get_children();//$product->get_available_variations("objects");

            $variations = [];

            foreach($variation_ids as $variation_id){
                $variations[] = new WC_Product_Variation($variation_id);
            }
            
            foreach($variations as $variation){
                if($variation->get_name() === $variation_name){
                    return $variation;
                }
            }
            return null;
        }

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

        public function removeProductVariation($product_id, $variation_name){
            //$this->logger->log("Deleting variation");

            $product = new WC_Product_Variable($product_id);

            $variation = $this->getVariationObjByName($product, $variation_name);
            if($variation){
                $variation->delete();
            }else{
                //error_log()
                throw new MyException("Could not find variation '$variation_name' in Product $product_id", $this, __METHOD__);
            }
            
        }

        public function getVariationByName($product_id, $variation_name){
            $product = new WC_Product_Variable($product_id);
            $variations = $product->get_available_variations();
            foreach($variations as $variation){
                if($variation["name"] === $variation_name){
                    return $variation;
                }
            }
            return null;
        }

        private function getProductVariations($product_id){
            $product = new WC_Product_Variable($product_id);

            return $product->get_available_variations();
        }

    }