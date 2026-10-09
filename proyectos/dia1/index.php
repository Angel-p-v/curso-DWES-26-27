<?php
session_start();
$mysqli = new mysqli("127.0.0.1", "root", "123456", "mariadb_database");

if(isset($_POST['login'])){
    $q = "SELECT * FROM users WHERE username = '".$_POST['nombre']."'";
    $result = $mysqli->query($q);
    echo "<br>";

    if($row = $result->fetch_assoc()){
        if($row['password_hash'] == md5($_POST['password'])){
            echo "login correcto";
            $_SESSION['username'] = $_POST['nombre'];
        } else {
            echo "contraseña o nombre de usuario incorrecto";
        }
    } else {
        echo "contraseña o nombre de usuario incorrecto";
    }
}

if(isset($_POST['registrarse'])){
    $q = "INSERT INTO users (username, password_hash) 
          VALUES ('".$_POST['nombre']."', '".md5($_POST['password'])."')";
    $result = $mysqli->query($q);
    if($result){
        echo "Usuario creado correctamente";
    } else {
        echo "Error al crear usuario";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

if(isset($_SESSION['username'])){
    echo "Bienvenido ".$_SESSION['username'];
    echo "<br>";
} else {
    echo "<a href='index.php?opc=login'>Login</a>";
    echo "<br>";
}

echo '<a href="index.php">Inicio</a>';
echo '<br>';

echo '<a href="index.php?opc=galeria">Galeria</a>';
echo '<br>';

echo '<a href="index.php?opc=video">Video</a>';
echo '<br>';

echo '<a href="index.php?opc=registrarse">Crear usuario</a>';
echo '<br>';
?>
<br>
<?php

$array[0] = "gato1.webp";
$array[1] = "gato2.webp";

if(isset($_SESSION['username'])){
    echo '<a href="index.php?opc=logout">Logout</a>';
    echo '<br>';
}


if(isset($_GET['opc'])){
    if($_GET['opc']=="logout"){
        session_destroy();
        header("Location: index.php");
        exit();
    }
    elseif($_GET['opc']=="galeria"){
        foreach($array as $a){
            echo '<img src="assets/'.$a.'" width="100px"/>';
        }
    } elseif($_GET['opc']=="video"){
        echo '<iframe src="https://www.youtube.com/embed/lv43uA0keJ8"></iframe>';
    } elseif($_GET['opc']=="registrarse"){
        echo '<form action="index.php" method="post">
            <input type="text" name="nombre" placeholder="Nombre de usuario">
            <input type="password" name="password" placeholder="Contraseña">
            <input type="submit" name="registrarse" value="Registrarse">
            </form>';

    } elseif($_GET['opc']=="login"){
        echo '<form action="index.php" method="post">
            <input type="text" name="nombre" placeholder="Nombre de usuario">
            <input type="password" name="password" placeholder="Contraseña">
            <input type="submit" name="login" value="Login">
            </form>';
    } else {
        echo "Bienvenido";
    }
}

?>

</body>
</html>