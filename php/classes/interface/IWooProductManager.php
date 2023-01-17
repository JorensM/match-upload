<?php

    interface IWooProductManager {

        /**
         * return single product
         * 
         * @param array $params key/value pairs of query params
         * 
         * @return array|null product or null if product not found
         */
        public function getProduct($id);
        
        /**
         * Return single product by sku
         * 
         * @param any $sku sku
         * 
         * @return WC_Product_Variable|null
         */
        public function getProductBySku($sku);

        /**
         * Check whether product with given sku exists
         * 
         * @param any $sku sku to check
         * 
         * @return bool true if product found, false if not
         */
        public function productExistsBySku($sku);

        /**
         * Create new product
         * 
         * @param array $params product params
         * 
         */
        public function createProduct(array $params);

        /**
         * Update existing product
         * 
         * @param $id id of product to update
         * @param array $params params to update
         */
        public function updateProduct($id, array $params);

        /**
         * Bulk update products. Possible to create, update and delete products
         * 
         * @param array $products_to_create products that will be created
         * @param array $products_to_update products that will be updated
         * @param array $products_to_delete product ids that will be deleted
         * 
         * @return array updated products in format ["update" => [], "create" =>, "delete" => []]
         */
        public function bulkUpdateProducts(array $products_to_create, array $products_to_update, array $products_to_delete);

        public function getVariationByName($product_id, string $variation_name);

        public function updateProductVariation($product_id, $variation_name, array $params);
        
        public function removeProductVariation($product_id, $variation_name);
    }