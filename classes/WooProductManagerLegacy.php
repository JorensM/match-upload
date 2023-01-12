<?php

    require_once(__DIR__."/../wp_init.php");
    require_once("interface/IWooProductManager.php");
    require_once(__DIR__."/../functions/printRPre.php");
    

    class WooProductManagerLegacy implements IWooProductManager{

        public function __constructor(){

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

            $name = array_key_exists("title", $params) ? $params["title"] : $product->get_title();
            $description = array_key_exists("description", $params) ? $params["description"] : $product->get_description();
            $categories = array_key_exists("categories", $params) ? $params["categories"] : $product->get_category_ids();
            $image_id = array_key_exists("image_id", $params) ? $params["image_id"] : $product->get_image_id();

            $product->set_name($name);
            $product->set_description($description);
            $product->set_category_ids($categories);
            $product->set_image_id($image_id);
        }

    }