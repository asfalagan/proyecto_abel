use racebook;
select * from usuario;
select * from usuario_organizador;
SELECT * FROM completados;
SELECT insertar_evento('a','a','a','a','a','a',1);
CREATE TABLE jsonPruebas (
	jsonUsuario json
);
#https://dev.mysql.com/doc/refman/8.0/en/json.html
SELECT id, nombre, nickname, email, passwd, salt,  imagen, fecha_nacimiento, email_is_admin('a@a.com') FROM usuario WHERE email = 'a@a.com';
UPDATE usuario SET nombre='fff' WHERE id = 14;
CALL completar_organizador (14, 'abel', 'abelin', '2022-02-16', 'jeje', '687526414', 'nombre');

DELIMITER $$
CREATE FUNCTION comprobar_registro(p_id INT)
RETURNS BOOLEAN
BEGIN
     DECLARE v_estado BOOLEAN;
	 SET v_estado = (SELECT * FROM completados WHERE id_usuario = p_id);
     RETURN v_estado;
END;
$$
DELIMITER ; 

SELECT comprobar_registro(29);

INSERT INTO usuario (nombre) VALUES ('aa');
INSERT INTO usuario_organizador (id_usuario) VALUES(last_insert_id());