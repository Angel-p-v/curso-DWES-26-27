<?php
// ---------------------------------------------------------------
// UserRepository.php = acceso a datos de usuarios
// Es la capa que habla con la BD: el resto del proyecto no hace SQL
// ---------------------------------------------------------------
class UserRepository {

    private $mysqli;

        // Busca un usuario por su id y devuelve el modelo User (o null si no existe)
        public static function getUserById($id){
            $mysqli = new mysqli("127.0.0.1", "root", "123456", "mariadb_database");

            $query = "SELECT * FROM usuarios WHERE id = $id";
            $result = $mysqli->query($query);

            if ($result && $row = $result->fetch_assoc()) {
                // La fila de la BD se convierte en un objeto User
                return new User($row['username'], (int)$row['id']);
            } else {
                return null;
            }
        }
}

?>