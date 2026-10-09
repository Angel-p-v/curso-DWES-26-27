<?php

class Order{

    private $id;
    private $buyer;
    private $total_price;
    private $date;
    private $status;
    private $orderLines=[];

    public function __construct($id, $buyer_id, $total_price, $date, $status) {
        $this->id = $id;
        $this->buyer = UserRepository::getUserById($buyer_id);
        $this->total_price = $total_price;
        $this->date = $date;
        $this->status = $status;  
        $this->orderLines = OrderLineRepository::getOrderLinesByOrderId($id);
    }

    public function getId() {
        return $this->id;
    }

    public function getBuyer() {
        return $this->user_id;
    }


    public function getTotal() {
        return $this->total_price;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getDate() {
        return $this->date;
    }

    public function getProducts() {
        return $this->products;
    }

    public function getOrderLines(){
        return $this->orderLines;
    }
}