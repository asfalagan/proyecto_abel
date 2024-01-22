DROP DATABASE IF EXISTS pruebas_api;

CREATE DATABASE IF NOT EXISTS pruebas_api
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE pruebas_api;

-- Crea el usuario asignándole contraseña
CREATE USER IF NOT EXISTS 'abeldes'@'localhost' IDENTIFIED BY '1234';
-- Asigna todos los permisos al usuario sobre la base de datos
GRANT ALL PRIVILEGES ON pruebas_api.* TO 'abeldes'@'localhost';

CREATE TABLE usuarios(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50),
    email VARCHAR(100),
    passwd VARCHAR(16)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarios (nombre, email, passwd) VALUES ('Abel', 'abel@email.com', '1234');