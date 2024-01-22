
DROP DATABASE IF EXISTS cine;

CREATE DATABASE IF NOT EXISTS cine
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE cine;

-- Crea el usuario asignándole contraseña
CREATE USER IF NOT EXISTS 'abeldes'@'localhost' IDENTIFIED BY '1234';
-- Asigna todos los permisos al usuario sobre la base de datos
GRANT ALL PRIVILEGES ON cine.* TO 'abeldes'@'localhost';


CREATE TABLE actores (
  id int PRIMARY KEY AUTO_INCREMENT,
  nombre varchar(30) NOT NULL,
  apellidos varchar(80) NOT NULL,
  foto varchar(124) DEFAULT 'No disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE peliculas (
    id int PRIMARY KEY AUTO_INCREMENT,
	  titulo varchar(30) NOT NULL,
	  director varchar(30) DEFAULT NULL,
    genero varchar(20) NOT NULL,
    pais varchar(40) NOT NULL,
    fecha date,
    cartel varchar(124) DEFAULT 'No disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE actor_pelicula (
  id_pelicula int NOT NULL,
  id_actor int NOT NULL,
  
  FOREIGN KEY (id_pelicula) REFERENCES peliculas (id) ON DELETE CASCADE,
  FOREIGN KEY (id_actor) REFERENCES actores (id) ON DELETE CASCADE,
  PRIMARY KEY (id_pelicula, id_actor)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO actores (nombre, apellidos) VALUES ('Antonio', 'Pérez');
INSERT INTO actores (nombre, apellidos) VALUES ('Raúl', 'Largo');
INSERT INTO peliculas(titulo, director, genero, pais, fecha) VALUES('Película Mala', 'Sr. Desastre', 'Drama', 'España', '2003-05-30');
INSERT INTO peliculas(titulo, director, genero, pais, fecha) VALUES('Otra Película Mala', 'Sr. Desconocido', 'Acción', 'España', '2023-02-24');
