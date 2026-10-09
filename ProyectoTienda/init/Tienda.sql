CREATE DATABASE IF NOT EXISTS tienda_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tienda_online;

-- 1. Tabla Usuario
-- Tabla Usuario (con contraseña añadida)
CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- 2. Tabla Categoria
CREATE TABLE Categoria (
    idCategoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- 3. Tabla Producto
CREATE TABLE Producto (
    idProducto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    idCategoria INT NOT NULL,
    CONSTRAINT fk_producto_categoria FOREIGN KEY (idCategoria)
        REFERENCES Categoria(idCategoria)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. Tabla Carrito (Relación 1:1 con Usuario)
CREATE TABLE Carrito (
    idCarrito INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT UNIQUE NOT NULL,
    CONSTRAINT fk_carrito_usuario FOREIGN KEY (idUsuario)
        REFERENCES Usuario(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Tabla Intermedia Carrito_Producto (Relación N:M)
CREATE TABLE Carrito_Producto (
    idCarrito INT NOT NULL,
    idProducto INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    PRIMARY KEY (idCarrito, idProducto),
    CONSTRAINT fk_cp_carrito FOREIGN KEY (idCarrito)
        REFERENCES Carrito(idCarrito)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cp_producto FOREIGN KEY (idProducto)
        REFERENCES Producto(idProducto)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 6. Tabla Pedido (Relación 1:N con Usuario)
CREATE TABLE Pedido (
    idPedido INT AUTO_INCREMENT PRIMARY KEY,
    fechaPed DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(50) NOT NULL DEFAULT 'Pendiente',
    total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    idUsuario INT NOT NULL,
    CONSTRAINT fk_pedido_usuario FOREIGN KEY (idUsuario)
        REFERENCES Usuario(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 7. Tabla Intermedia Pedido_Producto / DetallePedido (Relación N:M)
CREATE TABLE Pedido_Producto (
    idPedido INT NOT NULL,
    idProducto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_compra DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (idPedido, idProducto),
    CONSTRAINT fk_pp_pedido FOREIGN KEY (idPedido)
        REFERENCES Pedido(idPedido)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pp_producto FOREIGN KEY (idProducto)
        REFERENCES Producto(idProducto)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 8. Tabla Envio (Relación 1:1 con Pedido)
CREATE TABLE Envio (
    idEnvio INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    idPedido INT UNIQUE NOT NULL,
    CONSTRAINT fk_envio_pedido FOREIGN KEY (idPedido)
        REFERENCES Pedido(idPedido)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
