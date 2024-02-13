-- un trigger que actualiza la ubicacion de un evento cuando se inserta una carrera
-- el trigger se dispara despues de insertar una carrera
-- si el evento no tiene una ubicacion, se actualiza con la ubicacion de la primera carrera
-- la ubicacion corresponde al primer punto del recorrido de la carrera
-- solo inserta el primer elemento del subarray 


DELIMITER $$
CREATE TRIGGER actualizar_ubicacion
AFTER INSERT 
ON carrera FOR EACH ROW
BEGIN 
    DECLARE v_is_set BOOLEAN;
    DECLARE v_id_evento INT;
    DECLARE v_recorrido_carrera TEXT;
    DECLARE v_ubicacion_evento VARCHAR(128);
    
    SET v_id_evento = NEW.id_evento;
    SET v_is_set = (SELECT isset_ubicacion(v_id_evento));
    SET v_recorrido_carrera = NEW.recorrido;
    
    IF v_is_set = FALSE THEN
        SET v_ubicacion_evento = (SELECT JSON_UNQUOTE(JSON_EXTRACT(v_recorrido_carrera, '$[0].ubicacion')));
        UPDATE evento SET ubicacion = v_ubicacion_evento WHERE id = v_id_evento;
    END IF;
END;
$$
DELIMITER ;