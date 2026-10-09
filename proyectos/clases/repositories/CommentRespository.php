<?php
    class CommentRepository {
        private $mysqli;

        // Devuelve el array de comentarios del articulo indicado
        public static function getCommentsByPostId(int $post_id): array {
            $mysqli = new mysqli("127.0.0.1", "root", "123456", "mariadb_database");
            
            // Filtra por articulo_id: por eso el orden de los comentarios
            // siempre es el del post al que pertenecen
            $q = "SELECT * FROM comentarios WHERE articulo_id = $post_id";
            $result = $mysqli->query($q);

            $comments = [];
            // Cada fila de la tabla comentarios se convierte en un objeto Comment
            while ($row = $result->fetch_assoc()) {
                $comments[] = new Comment((int)$row['id'], (int)$row['articulo_id'], 
                $row['contenido'], $row['created_at'], (int)$row['usuario_id']);
            }
            return $comments;
        }
    }
?>