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
    //gestion del cartel
    if(isset($_FILES['cartel'])){// UPLOAD_ERR_OK da 0; no hay errores
        $cartel = $_FILES['cartel'];
        $rutaDestinoCartel = '../../img/';
        #muevo el archivo a la carpeta de destino
        $nombreCartel = basename($cartel['name']);
        #de esta forma nos aseguramos que cada imagen subida tiene url unica
        $nombreCartel = uniqid().date("Ymd").$nombreCartel;
        $rutaDestinoCartel .= $nombreCartel;
        move_uploaded_file($cartel["tmp_name"], $rutaDestinoCartel);
    }
    //gestion del reglamento
    if(isset($_FILES['reglamento'])){// UPLOAD_ERR_OK da 0; no hay errores
        $reglamento = $_FILES['reglamento'];
        $rutaDestinoReglamento = '../../ficheros/reglamentos/';
        #muevo el archivo a la carpeta de destino
        $nombreReglamento = basename($reglamento['name']);
        #de esta forma nos aseguramos que cada imagen subida tiene url unica
        $nombreReglamento = uniqid().date("Ymd").$nombreReglamento;
        $rutaDestinoReglamento .= $nombreReglamento;
        move_uploaded_file($reglamento["tmp_name"], $rutaDestinoReglamento);
    }
    $conexion = new Conexion;
    if($conexion->connect_error){
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    $sql = "INSERT INTO evento (id_organizador, nombre, localidad, provincia, fecha_inicio, fecha_fin, web, url_reglamento, url_cartel) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('issssssss', $usuarioId, $_POST['nombre'], $_POST['localidad'], $_POST['provincia'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['web'], $rutaDestinoReglamento, $rutaDestinoCartel);
    $stmt->execute();
    $stmt->close();
    $conexion->close();

    //obtengo el id del evento que acabo de insertar para devolverlo
    $conexion = new Conexion;
    if($conexion->connect_error){
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    $idEvento;
    $sql = "SELECT id FROM evento WHERE id_organizador = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $usuarioId);
    $stmt->execute();
    $stmt->bind_result($idEvento);
    $stmt->fetch();
    $stmt->close();
    $conexion->close();

    $eventData = [
        'eventoId' => $idEvento
    ];
    
    //creo un nuevo JWT que incluye los datos del usuario y los datos del evento
    $mtime = time() * 1000;
    $exp = $mtime + 3600;
    $userData = $decoded->userData;
    $payload = [
        'exp' => $exp,
        'userData' => $userData,
        'eventData' => $eventData
    ];
    $jwt = JWT::encode($payload, $jwtkey, 'HS256');
    header("HTTP/1.1 201 Registro de evento correcto");
    echo json_encode($jwt);
}

?>