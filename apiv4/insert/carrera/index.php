<?php
require_once '../../clases/conexion.php';
require_once '../../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;



header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
}



if($_SERVER['REQUEST_METHOD'] === 'POST'){

    //obtengo el JWT de la cabecera
    $headers = apache_request_headers();
    $header = $headers['Authorization'];
    //compruebo que empieza por bearer y despues elimino esa parte
    $token = str_replace('Bearer ', '', $header);
    //compruebo que el token es valido
    try{
        $decoded = JWT::decode($token, new key($jwtkey, 'HS256'));
    }catch(Exception $e){
        header("HTTP/1.1 404 Unauthorized");
        exit();
    }
    //obtengo el id usuario del $decoded
    $usuarioId = $decoded->userData->userId;
    //obtengo el id del evento del $decoded 
    $eventoId = $decoded->eventData->eventoId;
    $conexion = new Conexion;
    if($conexion->connect_error){
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    
    $sql = "INSERT INTO carrera (id_evento, nombre, modalidad, sexo, fecha_comienzo, hora_comienzo, fecha_nacim_min, fecha_nacim_max) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('isssssss', $eventoId, $_POST['nombre'], $_POST['modalidad'], $_POST['sexo'], $_POST['fechaComienzoCarrera'], $_POST['horaComienzoCarrera'], $_POST['fechaNacimMin'], $_POST['fechaNacimMax']);
    $stmt->execute();
    $stmt->close();
    $conexion->close();

    //obtengo el id de la carrera que acabo de insertar para devolverlo
    $carreraId;
    $conexion = new Conexion;
    if($conexion->connect_error){
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    $sql = "SELECT id FROM carrera WHERE id_evento = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $eventoId);
    $stmt->execute();
    $stmt->bind_result($carreraId);
    $stmt->fetch();
    $stmt->close();
    $conexion->close();
    $eventData = [
        'eventoId' => $eventoId
    ];
    $raceData = [
        'carreraId' => $carreraId
    ];
    
    //creo un nuevo JWT que incluye los datos del usuario y los datos del evento
    $mtime = time() * 1000;
    $exp = $mtime + 3600000;
    $userData = $decoded->userData;
    $payload = [
        'exp' => $exp,
        'userData' => $userData,
        'eventData' => $eventData,
        'raceData' => $raceData,
    ];
    $jwt = JWT::encode($payload, $jwtkey, 'HS256');
    header("HTTP/1.1 201 Registro de carrera correcto");
    echo json_encode($jwt);
}

?>