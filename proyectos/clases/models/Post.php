<?php

// ---------------------------------------------------------------
// Post.php = modelo de un articulo del blog
// Solo guarda datos y los devuelve con getters/setters.
// De la BD no se encarga: eso lo hace el controller.
// ---------------------------------------------------------------

class Post {

    // $id es la clave primaria en la tabla articulos
    private $id;
    private $titulo;
    private $contenido;
    // Objeto User con el autor del articulo
    private $author;
    private $comments;

    public function __construct(int $id, string $titulo, string $contenido) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->contenido = $contenido;
        $this->author = UserRepository::getUserById($id);
        $this->comments = CommentRepository::getCommentsByPostId($this->id);

    }

    // Necesario para el formulario de comentarios: viaja como campo oculto
    public function getId(): int { 
        return $this->id; 
    }

    public function getTitle(): string { 
        return $this->titulo; 
    }

    public function getContent(): string { 
        return $this->contenido; 
    }

    public function getAuthor(): User {
        return $this->author;
    }

    public function getArticuloId(){
        return $this->articulo_id;
    }

    // Devuelve el array de objetos Comment que tiene este articulo
    public function getComments(): array {
        // El modelo no hace SQL: se lo pide al repositorio
        return $this->comments;
        }
}