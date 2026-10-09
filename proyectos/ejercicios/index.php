<?php
    // --- CONFIGURACIÓN INICIAL ---
    // Inicia (o reanuda) la sesión para guardar datos del usuario entre páginas (login, nombre, etc.)
    session_start();
    // Conexión a la base de datos MariaDB (servidor, usuario, contraseña, nombre de la BD)
    $mysqli = new mysqli("127.0.0.1", "root", "123456", "mariadb_database");

    // --- CERRAR SESIÓN ---
    // Si el usuario pulsa "Cerrar sesión" (enlace index.php?opc=logout)
    if(isset($_GET['opc']) && $_GET['opc']=="logout"){
        session_destroy();                          // Borra todos los datos de la sesión en el servidor
        header("Location: index.php");              // Redirige a la portada (debe ir antes de imprimir HTML)
        exit();                                     // Detiene la ejecución del script
    }

    // --- PROCESAR FORMULARIOS ENVIADOS POR POST ---
    // Todos estos bloques se ejecutan cuando llega un formulario (método POST)

    // Login real
    if(isset($_POST['iniciar_sesion'])){
        // Busca al usuario por nombre
        $q = "SELECT * FROM usuarios WHERE username = '".$_POST['nombre']."'";
        $result = $mysqli->query($q);
        echo "<br>";

        if($row = $result->fetch_assoc()){          // Si el usuario existe
            if($row['password_hash'] == md5($_POST['password'])){ // Y la contraseña coincide
                echo "login correcto";
                $_SESSION['id'] = $row['id'];       // Guarda el id del usuario
                $_SESSION['username'] = $_POST['nombre']; // Y su nombre (para mostrar "Bienvenido" y menu)
            } else {
                echo "contraseña o nombre de usuario incorrecto";
            }
        } else {
            echo "contraseña o nombre de usuario incorrecto";
        }
    }

    // Registro de un nuevo usuario
    if(isset($_POST['registrarse'])){
        // Inserta el usuario nuevo; la contraseña se guarda cifrada con md5
        $q = "INSERT INTO usuarios (username, password_hash) 
              VALUES ('".$_POST['nombre']."', '".md5($_POST['password'])."')";
        $result = $mysqli->query($q);
        if($result){                                // Si la inserción funcionó
            echo "Usuario creado correctamente";
        } else {
            echo "Error al crear usuario";
        }
    }

    // Publicar un post nuevo (botón "Publicar" del formulario de postear)
    if(isset($_POST['publicar'])){
        if(isset($_SESSION['username'])){           // Solo puede publicar quien tiene sesión iniciada
            // Inserta el artículo en la tabla "articulos" con el id del usuario actual
            $q = "INSERT INTO articulos (titulo, contenido, usuario_id, created_at) 
                  VALUES ('".$_POST['titulo']."', '".$_POST['contenido']."', '".$_SESSION['id']."', NOW())";
            $result = $mysqli->query($q);
            if($result){
                echo "Post publicado correctamente";
            } else {
                echo "Error al publicar post";
            }
        } else {
            echo "Debes iniciar sesión para publicar";
        }
    }

    // Añadir un comentario (botón "Comentar" de cualquiera de los dos formularios)
    if(isset($_POST['comentar'])){
        // Se necesita tener sesión y que la URL traiga el id del post (..?opc=post&id=N o comentarios&id=N)
        if(isset($_SESSION['username']) && isset($_GET['id'])){
            $post_id = $_GET['id'];                 // id del post al que se comenta (viene en la URL)
            // Inserta el comentario en la tabla "comentarios" con el id del post y del usuario
            $q = "INSERT INTO comentarios (contenido, usuario_id, articulo_id) 
                  VALUES ('".$_POST['comentario']."', '".$_SESSION['id']."', '$post_id')";
            $result = $mysqli->query($q);
            if($result){
                echo "Comentario añadido correctamente";
            } else {
                echo "Error al añadir comentario";
            }
        } else {
            echo "Debes iniciar sesión para comentar";
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
    <h1>Bloc de articulos</h1>

    <?php 
    // --- MENÚ SUPERIOR (depende de si hay sesión iniciada) ---
    if(isset($_SESSION['username'])){               // Usuario logueado
        echo "Bienvenido " .($_SESSION['username']);
        echo"<br>";
        echo "<a href='index.php?opc=logout'>Cerrar sesión</a>"; // Enlace que dispara el logout
        echo"<br>";
        echo "<a href='index.php?opc=post'>Postear</a>";         // Enlace a la página de posts
        echo"<br>";
    } else {                                        // Usuario anónimo (sin sesión)
        echo "<a href='index.php?opc=register'>Crear cuenta</a>";
        echo"<br>";
        echo "<a href='index.php?opc=login'>Iniciar sesión</a>";
        echo"<br>";
    }


        // --- RUTAS DE LA PÁGINA: según el parámetro ?opc= de la URL ---
        if(isset($_GET['opc'])){

            // Página de REGISTRO: muestra el formulario para crear cuenta
            if($_GET['opc']=="register"){
                // Formulario que envía por POST los datos; se procesa en el bloque 'registrarse'
                echo '<form action="index.php?opc=register" method="POST">
                        <input type="text" name="nombre" placeholder="Nombre de usuario"/>
                        <input type="password" name="password" placeholder="Contraseña"/>
                        <input type="submit" name="registrarse" value="Registrarse"/>
                    </form>';

            // Página de LOGIN: muestra el formulario para iniciar sesión
            } elseif($_GET['opc']=="login"){
                // Formulario que envía por POST; lo procesa el bloque 'iniciar_sesion'
                echo '<form action="index.php?opc=login" method="POST">
                        <input type="text" name="nombre" placeholder="Nombre de usuario"/>
                        <input type="password" name="password" placeholder="Contraseña"/>
                        <input type="submit" name="iniciar_sesion" value="Iniciar sesión"/>
                    </form>';

            // Página de POSTS: formulario para publicar + listado de todos los posts con comentarios
            } elseif($_GET['opc']=="post"){
                if(isset($_SESSION['username'])){   // Solo el usuario logueado ve el formulario de publicar
                    // Formulario para crear un post nuevo (se procesa en el bloque 'publicar')
                    echo '<form action="index.php?opc=post" method="POST">
                            <input type="text" name="titulo" placeholder="Título del post"/>
                            <br>
                            <textarea name="contenido" placeholder="Escribe tu post aquí..."></textarea>
                            <br>
                            <input type="submit" name="publicar" value="Publicar"/>
                        </form>';
                }

                // --- LISTADO DE POSTS ---
                // Une "articulos" con "usuarios" para mostrar también quién escribió cada post
                $q = "SELECT articulos.id, articulos.titulo, articulos.contenido, usuarios.username, articulos.created_at 
                      FROM articulos 
                      JOIN usuarios ON articulos.usuario_id = usuarios.id 
                      ORDER BY articulos.created_at DESC";   // Ordenados de más reciente a más antiguo
                $result = $mysqli->query($q);
                while($row = $result->fetch_assoc()){   // Recorre los posts uno a uno
                    // Muestra el título, contenido y autor de cada post
                    echo "<h2>".$row['titulo']."</h2>";
                    echo "<p>".$row['contenido']."</p>";
                    echo "<p>Publicado por: ".$row['username']." el ".$row['created_at']."</p>";
                    // Enlace a la página individual de comentarios de este post
                    echo "<a href='index.php?opc=comentarios&id=".$row['id']."'>Comentarios</a>";
                    echo "<h4>Comentarios</h4>";

                    // --- COMENTARIOS DE CADA POST (en la página principal) ---
                    // Selecciona los comentarios de ESTE post junto con el nombre de quien comentó
                    $q2 = "SELECT comentarios.contenido, usuarios.username, comentarios.created_at 
                          FROM comentarios 
                          JOIN usuarios ON comentarios.usuario_id = usuarios.id 
                          WHERE comentarios.articulo_id = '".$row['id']."' 
                          ORDER BY comentarios.created_at DESC";   // Los más nuevos primero
                    $result2 = $mysqli->query($q2);
                    if($result2->num_rows == 0){        // Si no hay ningún comentario
                        echo "<p>Aún no hay comentarios</p>";
                    }
                    while($row2 = $result2->fetch_assoc()){ // Muestra cada comentario con su autor
                        echo "<p><strong>".$row2['username'].":</strong> ".$row2['contenido']."</p>";
                        echo "<p><small>".$row2['created_at']."</small></p>";
                    }

                    // --- FORMULARIO PARA COMENTAR DENTRO DEL POST ---
                    if(isset($_SESSION['username'])){
                        // Se envía a ?opc=post&id=N (id del post en la URL) y lo procesa 'comentar'
                        echo '<form action="index.php?opc=post&id='.$row['id'].'" method="POST">
                                <textarea name="comentario" placeholder="Escribe tu comentario aquí..."></textarea>
                                <br>
                                <input type="submit" name="comentar" value="Comentar"/>
                            </form>';
                    } else {
                        // Si no hay sesión, se le invita a iniciar sesión
                        echo "<a href='index.php?opc=login'>Inicia sesión para comentar</a>";
                    }

                    echo "<hr>";                    // Separador visual entre posts
                }

            // Página de salida: se procesa al principio, aquí solo se muestra el mensaje
            } elseif($_GET['opc']=="logout"){
                echo "Sesión cerrada";
            }

            // --- PÁGINA INDIVIDUAL DE COMENTARIOS DE UN POST (?opc=comentarios&id=N) ---
            if($_GET['opc']=="comentarios"){
                if(isset($_GET['id'])){             // Requiere que la URL indique qué post es
                    $post_id = $_GET['id'];         // Guarda el id del post
                    // Enlace para volver a la página principal de posts
                    echo "<a href='index.php?opc=post'>Volver a postear</a>";
                    echo "<br>";
                    // Consulta los datos del post concreto para mostrarlo arriba
                    $q = "SELECT articulos.titulo, articulos.contenido, usuarios.username, articulos.created_at 
                          FROM articulos 
                          JOIN usuarios ON articulos.usuario_id = usuarios.id 
                          WHERE articulos.id = '$post_id'";
                    $result = $mysqli->query($q);
                    if($row = $result->fetch_assoc()){  // Muestra el post seleccionado
                        echo "<h2>".$row['titulo']."</h2>";
                        echo "<p>".$row['contenido']."</p>";
                        echo "<p>Publicado por: ".$row['username']." el ".$row['created_at']."</p>";
                    }

                    // Formulario para escribir un comentario sobre este post (arriba, antes de la lista)
                    if(isset($_SESSION['username'])){
                        echo '<form action="index.php?opc=comentarios&id='.$post_id.'" method="POST">
                                <textarea name="comentario" placeholder="Escribe tu comentario aquí..."></textarea>
                                <br>
                                <input type="submit" name="comentar" value="Comentar"/>
                            </form>';
                    } else {
                        echo "Debes iniciar sesión para comentar";
                    }

                    // Lista los comentarios de este post junto al nombre de quien los escribió
                    $q = "SELECT comentarios.contenido, usuarios.username, comentarios.created_at 
                          FROM comentarios 
                          JOIN usuarios ON comentarios.usuario_id = usuarios.id 
                          WHERE comentarios.articulo_id = '$post_id' 
                          ORDER BY comentarios.created_at DESC";
                    $result = $mysqli->query($q);
                    while($row = $result->fetch_assoc()){
                        echo "<p>".$row['contenido']."</p>";
                        echo "<p>Comentado por: ".$row['username']." el ".$row['created_at']."</p>";
                        echo "<hr>";
                    }
                }
            }
        }
    ?>
</body>
</html>