DROP DATABASE IF EXISTS racebook;

CREATE DATABASE IF NOT EXISTS racebook
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE racebook;

-- Crea el usuario asignándole contraseña
CREATE USER IF NOT EXISTS 'abeldes'@'localhost' IDENTIFIED BY '1234';
-- Asigna todos los permisos al usuario sobre la base de datos
GRANT ALL PRIVILEGES ON racebook.* TO 'abeldes'@'localhost';

CREATE TABLE usuario(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50),
    nickname VARCHAR(50),
    email VARCHAR(100),
    passwd VARCHAR(32),
    jwtkey VARCHAR(32),
    imagen VARCHAR(64),
    fecha_nacimiento DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuario_organizador(
    id_usuario INT PRIMARY KEY,
    telefono VARCHAR(15),
    entidad_organizadora VARCHAR(64),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE evento(
	
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE imagen(

)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE carrera (
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_evento INT,
    nombre VARCHAR(50),
    modalidad ENUM('maratón', 'trail', 'ultra'),
    sexo ENUM('Hombres', 'Mujeres', 'Mixto'),
    fecha_nacim_min DATE,
    fecha_nacim_max DATE,
    likes INT,
    FOREIGN KEY (id_evento) REFERENCES evento(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;