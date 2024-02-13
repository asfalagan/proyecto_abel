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
    
    try {
        $decoded = JWT::decode($token, new key($jwtkey, 'HS256'));
    } catch (Exception $e) {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
    $idUsuario = $decoded->userData->userId;

    if($_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK && isset($_FILES['fotoPerfil'])){// UPLOAD_ERR_OK da 0; no hay errores
        $imagen = $_FILES['fotoPerfil'];
        $rutaDestino = '../../img/';
        #muevo el archivo a la carpeta de destino
        $nombreImagen = basename($imagen['name']);
        #de esta forma nos aseguramos que cada imagen subida tiene url unica
        $nombreImagen = uniqid().date("Ymd").$nombreImagen;
        $rutaDestino .= $nombreImagen;
        if(!is_dir($ruta_destino)){
            mkdir($ruta_destino, 0755, true);//ojo con los permisos que das aqui
        }
        move_uploaded_file($imagen["tmp_name"], $rutaDestino);
        
        $con = new Conexion();
        $sql = "UPDATE usuario SET nombre = ?, nickname = ?, fecha_nacimiento = ?, imagen = ? WHERE id = ?";
        if($stmt = $con -> prepare($sql)){
            $stmt -> bind_param("ssssi", $_POST['nombre'], $_POST['nickname'], $_POST['fechaNacimiento'], $rutaDestino, $idUsuario);
            $stmt -> execute();
            $stmt -> close();
            $con -> close();
        }else{
            header('HTTP/1.1 500 Internal Server Error');
        }

    }else{
        $con = new Conexion();
        $sql = "UPDATE usuario SET nombre = ?, nickname = ?, fecha_nacimiento = ? WHERE id = ?";
        if($stmt = $con -> prepare($sql)){
            $stmt -> bind_param("sssi", $_POST['nombre'], $_POST['nickname'], $_POST['fechaNacimiento'], $idUsuario);
            $stmt -> execute();
            $stmt -> close();
            $con -> close();
        }else{
            header('HTTP/1.1 500 Internal Server Error');
    }
    

    }
    if($decoded->userData->isAdmin){
        $con = new Conexion();
        $sql = "UPDATE usuario_organizador SET telefono = ?, entidad_organizadora = ? WHERE id_usuario = ?";
        if($stmt = $con -> prepare($sql)){
            $stmt -> bind_param("ssi", $_POST['telefono'], $_POST['entidadOrganizadora'], $idUsuario);
            $stmt -> execute();
            $stmt -> close();
            $con -> close();
        }else{
            header('HTTP/1.1 500 Internal Server Error');
        }
    }
    //creo un token con los nuevos datos del ususario 
    $pld = [
            'exp' => time() * 1000 + 3600,
            'userData' => [
                            'userId' => $idUsuario,
                            'isAdmin' => $decoded->userData->isAdmin,
                            'userNombre' => $_POST['nombre'],
                            'userNickname' => $_POST['nickname'],
                            'userFechaNacimiento' => $_POST['fechaNacimiento'],
                            'userImagen' => $rutaDestino,
                            'telefono' => $_POST['telefono'],
                            'entidadOrganizadora' => $_POST['entidadOrganizadora'],
                            'completado' => true
                        ]
    ];
    $token = JWT::encode($pld, $jwtkey, 'HS256');
    echo json_encode($token);
    header('HTTP/1.1 201 OK');
}
?>