CREATE DATABASE IF NOT EXISTS control_vencimientos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE control_vencimientos;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion VARCHAR(255),
    cantidad INT NOT NULL DEFAULT 0,
    fecha_vencimiento DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_vencimiento (fecha_vencimiento),
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB;

INSERT INTO productos (codigo,nombre,descripcion,cantidad,fecha_vencimiento) VALUES
('P001','Leche Entera','Producto de ejemplo',24,DATE_ADD(CURDATE(),INTERVAL 7 DAY)),
('P002','Galletas','Producto de ejemplo',50,DATE_ADD(CURDATE(),INTERVAL 45 DAY)),
('P003','Jugo de Naranja','Producto de ejemplo',18,DATE_SUB(CURDATE(),INTERVAL 3 DAY));
