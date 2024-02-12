<?php
require_once '../clases/conexion.php';
require_once '../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $json_data = file_get_contents("php://input");    

    //convierto el json data en texto plano
    $json_data = json_encode($json_data);
    $id = 1;










    $con = new Conexion();
    if(isset($json_data)){
        $sql = "INSERT INTO carrera (id_evento, recorrido) VALUES (?, ?)";
        if($stmt = $con -> prepare($sql)){
            $stmt -> bind_param("is", $id,  $json_data);
            $stmt -> execute();
            $stmt -> close();
            $con -> close();
            header("HTTP/1.1 201 OK");
            echo json_encode("Carrera creada");
        }else{
            header("HTTP/1.1 400 BadRequest");
            //muestro el error
            echo json_encode($con -> error);
        }
    }
}
?>