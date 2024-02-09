
<?php
require_once '../../../clases/conexion.php';
require_once '../../../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;



header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



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
    //gestion de la imagen
    if($_FILES['fotoPerfil']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
        $imagen = $_FILES['fotoPerfil'];
        $rutaDestino = '../../img/';
        #muevo el archivo a la carpeta de destino
        $nombreImagen = basename($imagen['name']);
        #de esta forma nos aseguramos que cada imagen subida tiene url unica
        $nombreImagen = uniqid().date("Ymd").$nombreImagen;
        $rutaDestino .= $nombreImagen;
        move_uploaded_file($imagen["tmp_name"], $rutaDestino);

    }
    $nombre = $_POST['nombre'];
    $nickname = $_POST['nickname'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    //$usuarioId 
    $imagen = $rutaDestino; //en la DB solo quiero guardar la url de la imagen
    $telefono = $_POST['telefono'];
    $entidadOrganizadora = $_POST['entidadOrganizadora'];

    $con = new Conexion();
    $sql = "CALL completar_organizador(?, ?, ?, ?, ?, ?, ?)";
    if($stmt = $con -> prepare($sql)){
        $stmt -> bind_param("issssss", $usuarioId, $nombre, $nickname, $fechaNacimiento, $imagen, $telefono, $entidadOrganizadora);
        $stmt -> execute();
        $stmt -> close();
        $con -> close();
        //agrego a este usuarioId a la tabla de completados
        $con = new Conexion();
        $sql = "INSERT INTO completados (usuario_id) VALUES (?)";
        if($stmt = $con -> prepare($sql)){
            $stmt -> bind_param("i", $usuarioId);
            $stmt -> execute();
            $stmt -> close();
            $con -> close();
        }else{
            header('HTTP/1.1 400 Bad Request');
            exit();
        }   
        header('HTTP/1.1 201 OK');
        //construyo un JWT nuevo con los datos del usuario
        $pld = [
            'exp' => time() * 1000 + 3600,
            'userData' => [
                'userId' => $userId,
                'isAdmin' => $isAdmin,
                'nombre' => $nombre,
            ]
        ];
        $token = JWT::encode($pld, $jwtkey, 'HS256');
        echo json_encode($token);
    }else{
        header("HTTP/1.1 202 Usuario no ha completado el registro");
        exit();
    
    }
}

?>