<?php

    class Product {
        private $name;
        private $price;
        private $description;
        private $id;

            public function __construct($name, $price, $description, $id){
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getId(){
        return $this->id;
    }
}