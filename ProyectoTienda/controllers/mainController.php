<?php

require_once("db.php");
$conn = db::connect();
require_once("models/User.php");
require_once("models/Product.php");

session_start();

if (isset($_GET['login'])) {
    require_once "views/login.phtml";
    exit();
}

if (isset($_GET['register'])) {
    require_once "views/register.phtml";
    exit();
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $q = "SELECT * FROM Usuario WHERE correo='{$_POST['correo']}'";
    $result = $conn->query($q);
    if (isset($_POST['correo']) && isset($_POST['password'])) {
        if ($row = $result->fetch_assoc()) {
            if ($row['password'] == md5($_POST['password'])) {
                $_SESSION['user'] = new User($row['nombre'], $row['correo'], $row['direccion'], $row['id']);
            } else {
                $info = "Contraseña incorrecta";
            }
        } else {
            $info = "El usuario no existe";
        }
    }
}

if (isset($_POST['register'])) {
    if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['password']) && isset($_POST['direccion'])) {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $password = md5($_POST['password']);
        $direccion = $_POST['direccion'];

        $qRegister = "INSERT INTO Usuario (nombre, correo, password, direccion) 
                      VALUES ('$nombre', '$correo', '$password', '$direccion')";

        if ($conn->query($qRegister) === TRUE) {
            $info = "Usuario registrado correctamente";
        } else {
            $info = "Error al registrar el usuario: " . $conn->error;
        }
    }
}

$products = [];
if (isset($_SESSION['user'])) {
    $qProducts = "SELECT * FROM Producto";
    $resultadoProducts = $conn->query($qProducts);

    if($resultadoProducts){
        while($row = $resultadoProducts->fetch_assoc()){
            $products[] = new Product(
                $row['nombre'],
                $row['precio'],
                $row['descripcion'],
                $row['idProducto']
            );
        }
    }
}


if(isset($_POST['add_to_cart']) && isset($_SESSION['user'])){
    $userId = $_SESSION['user']->getId();
    $productId = ($_POST['idProducto']);

    $ObtCarrito = $conn->query("SELECT idCarrito FROM Carrito WHERE idUsuario = $userId");
    if ($row = $ObtCarrito->fetch_assoc()){
        $idCarrito = $row['idCarrito'];
    } else {
        $conn->query("INSERT INTO Carrito (idUsuario) VALUES ($userId)");
        $idCarrito = $conn->insert_id;
    }

    $qInsert = "INSERT INTO Carrito_Producto (idCarrito, idProducto, cantidad)
                    VALUES ($idCarrito, $productId, 1)
                    ON DUPLICATE KEY UPDATE cantidad = cantidad + 1";
    $conn->query($qInsert);

    header("Location: index.php?cart");
    exit();
}

if (isset($_GET['cart'])) {
        $userId = $_SESSION['user']->getId();
        $cartItems = [];
        $totalPrice = 0;

        $qCart = "SELECT p.idProducto, p.nombre, p.precio, cp.cantidad, (p.precio * cp.cantidad) AS subtotal
                  FROM Carrito c
                  JOIN Carrito_Producto cp ON c.idCarrito = cp.idCarrito
                  JOIN Producto p ON cp.idProducto = p.idProducto
                  WHERE c.idUsuario = $userId";

        $resCart = $conn->query($qCart);

        if ($resCart) {
            while ($row = $resCart->fetch_assoc()) {
                $cartItems[] = $row;
                $totalPrice += $row['subtotal'];
            }
        }

        require_once "views/cartView.phtml";
        exit();
    }

require_once "views/mainView.phtml";
