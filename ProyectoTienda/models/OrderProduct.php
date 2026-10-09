<?php
    class OrderProduct {
        private $orderId;
        private $productId;
        private $quantity;
        private $salePrice;

        public function __construct($salePrice, $orderId, $productId, $quantity) {
            $this->orderId = $orderId;
            $this->productId = $productId;
            $this->quantity = $quantity;
            $this->salePrice = $salePrice;
        }


        public function getOrderId() {
            return $this->orderId;
        }

        public function getSalePrice() {
            return $this->salePrice;
        }

        public function getProductId() {
            return $this->productId;
        }

        public function getQuantity() {
            return $this->quantity;
        }
    }