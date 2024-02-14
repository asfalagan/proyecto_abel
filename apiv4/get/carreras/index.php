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
    
    //obtengo el idEvento del parametro de la url
    if(isset($_GET['idEvento'])){
        $idEvento = $_GET['idEvento'];
        $conexion = new Conexion;
        if($conexion->connect_error){
            header("HTTP/1.1 500 Error en la conexión a la base de datos");
            exit();
        }
        $sql = "SELECT * FROM carrera WHERE id_evento = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idEvento);
        $stmt->execute();
        $result = $stmt->get_result();
        $carreras = [];
        while($row = $result->fetch_assoc()){
            $carreras[] = $row;
        }
        $stmt->close();
        $conexion->close();
        header("HTTP/1.1 201 OK");
        //echo json_encode($carreras);
        echo json_encode($carreras);
    }else{
        header("HTTP/1.1 400 Bad Request");
        exit();
    }
}
?>