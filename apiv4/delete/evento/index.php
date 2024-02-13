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

    //compruebo que el token sea valido
    $headers = apache_request_headers();
    $header = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $header);
    //obtengo las variables del cuerpo de la peticion
    $json = file_get_contents('php://input');
    $datos = json_decode($json);
    $idEvento = $datos->idEvento;
    try {
        $decoded = JWT::decode($token, new key($jwtkey, 'HS256'));
    } catch (Exception $e) {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
    
    $con = new Conexion();
    $sql = "DELETE FROM evento WHERE id = ?";
    if($stmt = $con -> prepare($sql)){
        $stmt -> bind_param("i", $idEvento);
        $stmt -> execute();
        $stmt -> close();
        $con -> close();
        header('HTTP/1.1 201 OK');
    }else{
        header('HTTP/1.1 500 Internal Server Error');
    }
}
?>