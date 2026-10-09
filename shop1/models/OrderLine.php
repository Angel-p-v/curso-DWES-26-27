<?php

class OrderLine{

    private $id;
    private $product;
    private $quantity;
    private $price;

    public function __construct($id, $product_id, $quantity, $price) {
        $this->id = $id;
        $this->product = ProductRepository::getProductById($product_id);
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getId() {
        return $this->id;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getOrder() {
        return $this->order;
    }
}