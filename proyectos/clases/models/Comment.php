<?php
    // ---------------------------------------------------------------
    // Comment.php = modelo de un comentario
    // Cada comentario pertenece a un articulo (post_id) y a un usuario (usuario_id)
    // ---------------------------------------------------------------
    class Comment {
        // $id es la clave primaria en la tabla comentarios
        private int $id;
        // Articulo al que pertenece este comentario (columna articulo_id)
        private int $post_id;
        private string $content;
        // Fecha en la que se creo el comentario
        private string $created_at;
        // Usuario que escribio el comentario
        private int $usuario_id;


        public function __construct(int $id, int $post_id, string $content, string $created_at, int $usuario_id) {
            $this->id = $id;
            $this->post_id = $post_id;
            $this->content = $content;
            $this->created_at = $created_at;
            $this->usuario_id = $usuario_id;
        }

        public function getId(): int { 
            return $this->id; 
        }

        public function getContent(): string { 
            return $this->content; 
        }

        public function getPostId(): int { 
            return $this->post_id; 
        }

        // Fecha del comentario, para mostrarla junto al texto
        public function getCreatedAt(): string { 
            return $this->created_at; 
        }

        // Con quien se puede consultar el nombre del autor via UserRepository
        public function getUsuarioId(): int { 
            return $this->usuario_id; 
        }
        
    }