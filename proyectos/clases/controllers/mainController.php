<?php


// Modelos y repositorios: se cargan aqui para que las vistas puedan usarlos
require_once "models/Post.php";
require_once "models/User.php";
require_once "repositories/UserRepository.php";
require_once "models/Comment.php";
require_once "repositories/CommentRespository.php";

require_once "views/mainView.phtml";

// El login: comprueba nombre + contraseña y guarda el usuario en la sesión
if (isset($_POST['login'])) {
    $stmt = $mysqli->prepare("SELECT id, username, password_hash FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $_POST['nombre']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['password_hash'] == md5($_POST['password'])) {
            echo "Login exitoso, bienvenido " . $row['username'];
            // La sesión es lo que identifica al usuario en las siguientes peticiones
            $_SESSION['nombre'] = new User($row['username'], (int)$row['id'], $row['password_hash']);
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
}

// El crearse una cuenta: registra un usuario nuevo (contraseña guardada como md5)
if (isset($_POST['crear_cuenta'])) {
    $stmt = $mysqli->prepare("INSERT INTO usuarios (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['nombre'], md5($_POST['password']));
    if ($stmt->execute()) {
        echo "Cuenta creada exitosamente";
    } else {
        echo "Error al crear la cuenta";
    }
}

// Publicar articulo: solo si hay sesión iniciada, y busca el id del autor
if (isset($_POST['publicar'])) {
    if (isset($_SESSION['nombre'])) {
        if (!empty($_POST['titulo']) && !empty($_POST['contenido'])) {
            // El usuario en sesión ya es un objeto User, así que su id sale del propio modelo
            $user_id = $_SESSION['nombre']->getId();

            // Modelo: valida y guarda los datos del articulo
            $post = new Post($user_id, $_POST['titulo'], $_POST['contenido']);

            // Inserción sin nombres de columnas (debe coincidir con la estructura de la tabla)
            $stmt = $mysqli->prepare("INSERT INTO articulos (titulo, contenido, usuario_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $post->getTitle(), $post->getContent(), $user_id);

            if ($stmt->execute()) {
                // Redirige para que al recargar no se publique el articulo dos veces
                header("Location: index.php");
                exit();
            } else {
                echo "Error al publicar el articulo";
            }
        }
    } else {
        echo 'Debes iniciar sesion para publicar un articulo';
    }
}

//Ver articulos publicados: se pasan a la vista como $result y como el array de modelos $posts
$q = "SELECT * FROM articulos";
$result = $mysqli->query($q);
$posts = [];
if ($result) {
    // Cada fila de la tabla se convierte en un objeto Post
    while($row = $result->fetch_assoc()){
        $posts[] = new Post((int)$row['id'], $row['titulo'], $row['contenido']);
    }
}

// Publicar comentario: el id del articulo viaja en un campo oculto del formulario
if (isset($_POST['comentar'])) {
    // Solo se puede comentar con la sesión iniciada
    if (isset($_SESSION['nombre'])) {
        if (!empty($_POST['contenido']) && !empty($_POST['articulo_id'])) {
            $idUsuario = $_SESSION['nombre']->getId();
            $articulo_id = (int)$_POST['articulo_id'];
            $contenido = $_POST['contenido'];

            $stmt = $mysqli->prepare("INSERT INTO comentarios (contenido, usuario_id, articulo_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $contenido, $idUsuario, $articulo_id);

            if ($stmt->execute()) {
                // Redirige a la lista para que al recargar no se comente dos veces
                header("Location: index.php?opc=articulos");
                exit();
            } else {
                echo "Error al publicar el comentario";
            }
        }
    } else {
        echo 'Debes iniciar sesion para comentar';
    }
}

    // Esta vista no tiene logica propia: decide que pintar segun el valor de ?opc=.
    // Los datos ($result, $posts) ya los ha dejado listos mainController.php.
    if(isset($_GET['opc'])){
//Login: delega el formulario en login.phtml
        if($_GET['opc']=="login"){
            require_once "views/login.phtml";
//Crear cuenta: el submit se llama crear_cuenta, que es lo que mira el controller
        }
        if($_GET['opc']=="crear_cuenta"){
            require_once "views/crearCuenta.phtml";
        }
        if($_GET['opc']=="articulos"){
            require_once "views/articulosView.phtml";
        }
        //Logout: se destruye la sesión y se vuelve a inicio
        if($_GET['opc']=="logout"){
        session_destroy();
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
}

// Eleccion de vista: se ejecuta siempre, despues de todos los POST de arriba
if (isset($_GET['opc']) && $_GET['opc'] === 'redactar') {
    require_once "views/newPost.phtml";
}
?>