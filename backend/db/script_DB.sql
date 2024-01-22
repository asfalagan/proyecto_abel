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
    nickname VARCHAR(64),
    email VARCHAR(128),
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
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(64),
    fecha_inicio DATE,
    fecha_fin DATE,
    web VARCHAR(128),
    url_reglamento VARCHAR(128),
    likes INT
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE imagen(
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_evento INT,
    url VARCHAR(128),
    nombre VARCHAR(64),
    descripcion VARCHAR(128),
    FOREIGN KEY (id_EVENTO) REFERENCES evento(id)
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
CREATE TABLE recorrido(
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_carrera INT,
    nombre VARCHAR(64),
    desnivel FLOAT,
    distancia FLOAT,
    dificultad ENUM('1', '2', '3', '4', '5'),
    FOREIGN KEY (id_carrera) REFERENCES carrera(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE coordenada(
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_recorrido INT,
    longitud FLOAT,
    latitud FLOAT,
    orden INT,
    FOREIGN KEY (id_recorrido) REFERENCES recorrido(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE premio(
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_carrera INT,
    puesto INT,
    trofeo BOOLEAN,
    FOREIGN KEY (id_carrera) REFERENCES carrera(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE dinero(
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_premio INT,
    cantidad FLOAT,
    FOREIGN KEY (id_premio) REFERENCES premio(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE detalle(
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_premio INT,
    descripcion VARCHAR(512),
    FOREIGN KEY (id_premio) REFERENCES premio(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE clasificacion(
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_carrera INT, 
    fecha DATE,
    tiempo TIME,
    puesto INT,
    FOREIGN KEY (id_carrera) REFERENCES carrera(id),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE usuario_inscribe_carrera(
	id_usuario INT,
    id_carrera INT,
    fecha_inscripcion DATETIME DEFAULT CURRENT_TIMESTAMP,
    talla_camiseta VARCHAR(3),
    FOREIGN KEY (id_carrera) REFERENCES carrera(id),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    PRIMARY KEY (id_usuario, id_carrera)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE usuario_carrera_favorita(
	id_usuario INT,
    id_carrera INT,
    FOREIGN KEY (id_carrera) REFERENCES carrera(id),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO usuario (email, passwd) VALUES ('abel@email.com','1234');