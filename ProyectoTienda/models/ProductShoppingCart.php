<?php

    class ProductSShoppingCart{
        private $idProduct;
        private $idShoppingCart;
        private $quantity;

        public function __construct($idProduct, $idShoppingCart, $quantity){
            $this->idProduct = $idProduct;
            $this->idShoppingCart = $idShoppingCart;
            $this->quantity = $quantity;
        }

        public function getIdProduct(){
            return $this->idProduct;
        }

        public function getIdShoppingCart(){
            return $this->idShoppingCart;
        }

        public function getQuantity(){
            return $this->quantity;
        }
    }