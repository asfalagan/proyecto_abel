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
    $conexion = new Conexion;
    if($conexion->connect_error){
        header("HTTP/1.1 500 Error en la conexión a la base de datos");
        exit();
    }
    $sql = "SELECT * FROM evento";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $eventos = [];
    while($row = $result->fetch_assoc()){
        $eventos[] = $row;
    }
    $stmt->close();
    $conexion->close();
    header("HTTP/1.1 201 OK");
    echo json_encode($eventos);
}
?>