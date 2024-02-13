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

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    
    //compruebo que el token sea valido
    $headers = apache_request_headers();
    $header = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $header);
    
    try {
        $decoded = JWT::decode($token, new key($jwtkey, 'HS256'));
    } catch (Exception $e) {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
    $idOrganizador = $decoded->userData->userId;
    
    $conexion = new Conexion;
    if($conexion->connect_error){
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    $sql = "SELECT * FROM evento WHERE id_organizador = $idOrganizador";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $eventos = [];
    while($row = $result->fetch_assoc()){
        $eventos[] = $row;
    }
    //creo un nuevo JWT
    $payload = [
        'exp' => time() * 1000 + 3600,
        'userData' => $decoded->userData,
        'userEventos' => $eventos
    ];
    
    
    $stmt->close();
    $conexion->close();
    $jwtEncode = JWT::encode($payload, $jwtkey, 'HS256'); 
    header("HTTP/1.1 201");
    echo json_encode($jwtEncode);
    exit();
}
?>