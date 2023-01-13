<?php

    require_once(__DIR__."/../wp_init.php");

    require_once("interface/IWooProductManager.php");
    require_once("interface/ILogger.php");

    require_once(__DIR__."/../functions/printRPre.php");
    

    class WooProductManagerLegacy implements IWooProductManager{

        private ILogger $logger;

        public function __constructor(ILogger $logger){
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

        private function assocArrayFromProduct($product){
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


        public function updateProduct($id, array $params){
            $product = new WC_Product_Variable($id);

            echo "Will update the following params: " . implode(", ", array_keys($params)) . PHP_EOL;

            $name = array_key_exists("title", $params) ? $params["title"] : $product->get_title();
            $description = array_key_exists("description", $params) ? $params["description"] : $product->get_description();
            $categories = array_key_exists("categories", $params) ? $params["categories"] : $product->get_category_ids();
            $image_id = array_key_exists("image_id", $params) ? $params["image_id"] : $product->get_image_id();
            

            //$variations = $product->get_available_variations();

            //printRPre($variations);

            $product->set_name($name);
            $product->set_description($description);
            $product->set_category_ids($categories);
            $product->set_image_id($image_id);

            $product->save();
        }

        public function updateProductVariation($product_id, $variation_name, array $params){
            $product = new WC_Product_Variable($product_id);

            $variation = $this->getVariationByName($product, $variation_name);

            $price = array_key_exists("regular_price", $params) ? $params["regular_price"] : $variation->get_regular_price();
            $manage_stock = array_key_exists("manage_stock", $params) ? $params["manage_stock"] : $variation->get_manage_stock();

            $variation->set_regular_price($price);
            $variation->set_manage_stock($manage_stock);

            $variation->save();

        }

        public function removeProductVariation($product_id, $variation_name){
            $product = new WC_Product_Variable($product_id);

            $variation = $this->getVariationByName($product, $variation_name);
            echo "c";
            if($variation){
                echo "todelete \n";
                $variation->delete();
                echo "deleted \n";
            }else{
                echo "should throw error";

                //error_log()
                throw new MyException("Could not find variation '$variation_name' in Product $product_id", $this, __METHOD__);
            }
            
        }

        private function getVariationByName(WC_Product_Variable $product, $variation_name){
            $variations = $product->get_available_variations("objects");
            foreach($variations as $variation){
                if($variation->get_name() === $variation_name){
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