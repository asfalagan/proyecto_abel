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
    email VARCHAR(128) UNIQUE,
    passwd VARCHAR(64),
    salt VARCHAR(10),
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
    localidad VARCHAR(64),
    provincia VARCHAR(64),
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

CREATE TABLE completados(
	id_usuario INT PRIMARY KEY,
    FOREIGN KEY (id_usuario) REFERENCES usuario (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DELIMITER $$
CREATE FUNCTION email_is_admin(p_email VARCHAR(128))
RETURNS BOOLEAN
BEGIN 
	DECLARE v_is_admin INT;
    SET v_is_admin = (SELECT id_usuario FROM usuario_organizador UO JOIN usuario U ON UO.id_usuario = U.id WHERE U.email = p_email);
    IF v_is_admin IS NOT NULL THEN
		RETURN TRUE;
	ELSE 
		RETURN FALSE;
	END IF;
END;
$$
DELIMITER ;
DELIMITER $$
CREATE FUNCTION id_is_admin(p_id INT)
RETURNS BOOLEAN
BEGIN 
	DECLARE v_is_admin INT;
    SET v_is_admin = (SELECT id_usuario FROM usuario_organizador UO JOIN usuario U ON UO.id_usuario = U.id WHERE U.id = p_id);
    IF v_is_admin IS NOT NULL THEN
		RETURN TRUE;
	ELSE 
		RETURN FALSE;
	END IF;
END;
$$
DELIMITER ;
DELIMITER $$
CREATE PROCEDURE insertar_organizador(p_email VARCHAR(128) , p_passwd VARCHAR(64), p_salt VARCHAR(10))
BEGIN
	 DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
 
 
    START TRANSACTION;
 
        INSERT INTO usuario (email, passwd, salt) 
			VALUES (p_email, p_passwd, p_salt);
        INSERT INTO usuario_organizador (id_usuario)
            VALUES (LAST_INSERT_ID());
		
            
    COMMIT;
END
$$
DELIMITER ; 
DELIMITER $$
CREATE PROCEDURE completar_organizador(p_id INT, p_nombre VARCHAR(128) , p_nickname VARCHAR(128), p_fecha_nacimiento DATE, p_imagen VARCHAR(128), p_telefono VARCHAR(15), p_entidad_organizadora VARCHAR(64))

BEGIN
	 DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
 
 
    START TRANSACTION;
 
        UPDATE usuario 
			SET nombre = p_nombre, nickname = p_nickname, fecha_nacimiento = p_fecha_nacimiento, imagen = p_imagen
            WHERE id = p_id;
        UPDATE usuario_organizador 
			SET telefono = p_telefono, entidad_organizadora = p_entidad_organizadora
            WHERE id_usuario = p_id;
		INSERT INTO completados (id_usuario)
			VALUES (p_id);
    COMMIT;
END
$$
DELIMITER ; 
DELIMITER $$
CREATE FUNCTION comprobar_registro(p_id INT)
RETURNS INT
BEGIN
     DECLARE v_estado BOOLEAN;
	 SET v_estado = (SELECT * FROM completados WHERE id_usuario = p_id);
     RETURN v_estado;
END;
$$
DELIMITER ; 

# SELECT is_admin('abel@email.com');
