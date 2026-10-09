<?php
// ---------------------------------------------------------------
// index.php = punto de entrada (front controller)
// Todo el proyecto arranca aqui: es la unica pagina pública.
// ---------------------------------------------------------------

// Las clases se cargan ANTES de session_start(): al iniciar la sesión PHP
// deserializa $_SESSION, y si User no está definida en ese momento el objeto
// vuelve como __PHP_Incomplete_Class y no tiene métodos (getId() fallaría)
require_once "models/Post.php";
require_once "models/User.php";
require_once "models/Comment.php";

// Si sale fatal error, session_destroy() hay que moverlo debajo de la clase
// Inicia la sesión: es lo que permite guardar $_SESSION['nombre'] al hacer login
session_start();

// Conexión a MariaDB/MySQL, se comparte con el controller a través de la variable $mysqli
$mysqli = new mysqli("127.0.0.1", "root", "123456", "mariadb_database");

// El controller procesa los formularios y decide que vista se muestra
require_once "controllers/mainController.php";

?>


