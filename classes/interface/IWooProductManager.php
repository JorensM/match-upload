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

        public function bulkUpdateProducts(array $products);

        public function getVariationByName($product_id, string $variation_name);

        public function updateProductVariation($product_id, $variation_name, array $params);
        
        public function removeProductVariation($product_id, $variation_name);
    }