<?php
require_once '../../clases/conexion.php';
require_once '../../../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;



header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //gestion de la imagen
     if($_FILES['imagen']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
        $imagen = $_FILES['imagen'];
        $rutaDestino = '../../../img/usuarios/';
        #si la ruta de destino no existe -> la creo
        if(!is_dir($rutaDestino)){
            mkdir($rutaDestino, 0777, true);//ojo con los permisos que das aqui
        }
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
    $userId = $_POST['userId'];
    $imagen = $rutaDestino; //en la DB solo quiero guardar la url de la imagen
    
    $con = new Conexion();
    $sql = "UPDATE usuario SET nombre = ?, nickname = ?, fecha_nacimiento = ?, imagen = ? WHERE id = ?";
    if($stmt = $con -> prepare($sql)){
        $stmt -> bind_param("ssssi", $nombre, $nickname, $fechaNacimiento, $imagen, $userId);
        $stmt -> execute();
        $stmt -> close();
        $con -> close();
        $con = new Conexion();
        $sql = 'INSERT INTO completados (id_usuario) VALUES (?)';
        if($stmt = $con -> prepare($sql)){
            $stmt -> bind_param("i", $userId);
            $stmt -> execute();
            $stmt -> close();
            $con -> close();
        }else{
            header('HTTP/1.1 400 Bad Request');
            echo 'Error al preparar la consulta: ' . $con->error;
            exit();
        }
        header('HTTP/1.1 201 OK');
        echo 'Lo tenemos pero no sabemos bien que ocurre';
        exit();    
    }else{
        header('HTTP/1.1 400 Bad Request');
        echo 'Error al preparar la consulta: ' . $con->error;
        exit();
    
    }

}else{
    header('HTTP/1.1 404 Bad Request');
}

?>