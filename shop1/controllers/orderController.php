<?php

echo "<a href='index.php'>volver</a><br>";

if(isset($_GET['add'])){

    //sacar el producto de la base de datos
    if(isset($_POST['id']) && isset($_POST['quantity'])){
    $product=ProductRepository::getProductById($_POST['id']);

    //tener el pedido en estado carrito del usuario
   $order=OrderRepository::getCarritoByUserId($_SESSION['user']->getId());

    // crear un orderline en pedido de usuario con producto
    if(OrderLineRepository::addOrderLineToOrder($order,$product,$_POST['quantity'])){
        //actualizar total del pedido
        
        $newTotal= $order->getTotal()+($product->getPrice()*$_POST['quantity']);
        $db=DB::connect();
        $q="UPDATE orders SET total_price=".$newTotal." WHERE id=".$order->getId();
        $db->query($q);

        header('location: index.php?c=order&show');
        exit;
    }

}
//devolviendo a la vista del carrito
   header('location: index.php');
   exit; 
}

if(isset($_GET['show'])){
    $order=OrderRepository::getCarritoByUserId($_SESSION['user']->getId());
    require_once('views/showOrderView.phtml');
    exit;
}


if(isset($_GET['id'])){
    $order=OrderRepository::getCarritoByUserId($_SESSION['user']->getId());
    $orderLine=OrderLineRepository::getOrderLineById($_GET['id']);
    
    $db=DB::connect();
    $q= "DELETE FROM order_lines WHERE id=".$_GET['id'];
    $db->query($q);
    if($db->affected_rows>0){
        $newTotal=$order->getTotal()-($orderLine->getPrice()*$orderLine->getQuantity());
        $q="UPDATE orders SET total_price=".$newTotal." WHERE id=".$order->getId();
        $db->query($q);
    }
}

if(isset($_GET['update'])){
    $order=OrderRepository::getCarritoByUserId($_SESSION['user']->getId());
    $q = "UPDATE orders SET status=1 WHERE id=".$order->getId();
    $db=DB::connect();
    $db->query($q);
}

if(isset($_GET['orders'])){
    require_once('views/ordersView.phtml');
    exit;
}

header('location: index.php?c=order&show');
exit;