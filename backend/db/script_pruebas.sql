use racebook;
select * from usuario;
select * from usuario_organizador;

CREATE TABLE jsonPruebas (
	jsonUsuario json
);
#https://dev.mysql.com/doc/refman/8.0/en/json.html

UPDATE usuario SET nombre='fff' WHERE id = 14;
CALL completar_organizador (14, 'abel', 'abelin', '2022-02-16', 'jeje', '687526414', 'nombre');

DELIMITER $$
CREATE FUNCTION comprobar_registro(p_id INT)
RETURNS BOOLEAN
BEGIN
     DECLARE v_estado BOOLEAN;
	 SET v_estado = (SELECT completado FROM completados WHERE id_usuario = p_id);
     RETURN v_estado;
END;
$$
DELIMITER ; 
select * FROM completados;
SELECT comprobar_registro(29);