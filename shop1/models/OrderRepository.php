<?php

class OrderRepository{

    public static function getOrderById($id){
          $db=DB::connect();
        $query="SELECT * FROM orders WHERE id=".$id;
        $result=$db->query($query);
        $order=$result->fetch_assoc();
        return new Order($order['id'], $order['buyer_id'], $order['total_price'], $order['date'], $order['status'] );
 
    }

    public static function getCarritoByUserId($id){
        $db=DB::connect();
        $q='SELECT * from orders where status=0 and buyer_id='.$id;

        $result=$db->query($q);
        if($row=$result->fetch_assoc()){
            return new Order($row['id'], $row['buyer_id'], $row['total_price'], $row['date'], $row['status']);
        }

        $insertQ='INSERT into orders VALUES (null, '.$id.', 0, NOW(), 0)';
        if($db->query($insertQ)){
            $result=$db->query($q);
            if($row=$result->fetch_assoc()){
                return new Order($row['id'], $row['buyer_id'], $row['total_price'], $row['date'], $row['status']);
            }
        }

    return false;
    }
}