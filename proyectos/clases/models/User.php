<?php
class User{
    // Clave primaria, la usan otros repositorios para consultar usuarios
    private int $id;
    private string $name;
    // OJO: la contraseña no debería guardarse nunca en memoria
    private ?string $password;


// Crea el usuario. El controller lo usa al sacarlo de la base de datos
public function __construct(string $name, int $id, ?string $password = null){
    $this->name = $name;
    $this->id = $id;
    $this->password = $password;
}

// Nombre de usuario, es lo que se guarda en $_SESSION['nombre']
public function getName(): string{
    return $this->name;
}

    public function getId(): int {
        return $this->id;
    }

}
?>