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
    //compruebo los headers
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
    $json_data = file_get_contents("php://input");    
    //convierto el json data en texto plano
    $json_data = json_encode($json_data);
    $idUsuario = $decoded->userData->userId;
    $idEvento = $decoded->eventData->eventoId;
    $idCarrera = $decoded->raceData->carreraId;
    $con = new Conexion();
    $prueba = "prueba";
    if(isset($json_data)){
        $sql = "UPDATE carrera SET recorrido = ? WHERE id = ?";
        if($stmt = $con -> prepare($sql)){
            $stmt -> bind_param("si",  $json_data, $idCarrera);
            $stmt -> execute();
            $stmt -> close();
            $con -> close();
            
            //creo el jwt
            $userData = $decoded->userData;
            $eventData = $decoded->eventData;
            $raceData = $decoded->raceData;
            $expTime = time() * 1000 + 3600000;
            $payload = [
                "exp" => $expTime,
                "userData" => $userData,
                "eventData" => $eventData,
                "raceData" => $raceData,
            ];
            $jwt = JWT::encode($payload, $jwtkey, 'HS256');
            header("HTTP/1.1 201 OK");
            echo json_encode($jwt);
            
        }else{
            header("HTTP/1.1 400 BadRequest");
            //muestro el error
            echo json_encode($con -> error);
        }
    }
}
?>