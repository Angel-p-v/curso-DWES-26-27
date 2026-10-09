<?php

//cargar modelos
require_once("models/User.php");
require_once("models/Product.php");
require_once("models/Order.php");
require_once("models/OrderLine.php");
require_once("models/ProductRepository.php");
require_once("models/OrderRepository.php");
require_once("models/OrderLineRepository.php");
require_once("models/UserRepository.php");

session_start();

if(isset($_GET['c'])){
    require_once("controllers/".$_GET['c']."Controller.php");
}

//acciones

//listar productos

//ver login
if(isset($_GET['login'])){
    require_once('views/login.phtml');
    exit;
}
//logout
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit;
}

//register
if(isset($_GET['register'])){
    require_once('views/register.phtml');
    exit;
}


$products=ProductRepository::getProducts();

require_once("views/mainView.phtml");

?>