<?php

class ProductRepository{

    public static function getProducts(){
        $db=DB::connect();
        $query="SELECT * FROM products";
        $result=$db->query($query);
        $products=[];
        while($product=$result->fetch_assoc()){
            $products[]=new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['stock']);
        }
        return $products;
    }

    public static function getProductById($id){
        $db=DB::connect();
        $query="SELECT * FROM products WHERE id=$id";
        $result=$db->query($query);
        $product=$result->fetch_assoc();
        return new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['stock']);
    }
}