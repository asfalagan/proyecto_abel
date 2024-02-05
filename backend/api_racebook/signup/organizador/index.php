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
        $rutaDestino = '../../../img/organizadores/';
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
    $telefono = $_POST['telefono'];
    $entidadOrganizadora = $_POST['entidadOrganizadora'];
    echo $userId;
    echo '<br>';
    echo $nombre;
    echo '<br>';
    echo $nickname;
    echo '<br>';
    echo $fechaNacimiento;
    echo '<br>';
    echo $imagen;
    echo '<br>';
    echo $telefono;
    echo '<br>';
    echo $entidadOrganizadora;

    $con = new Conexion();
    $sql = "CALL completar_organizador(?, ?, ?, ?, ?, ?, ?)";
    if($stmt = $con -> prepare($sql)){
        $stmt -> bind_param("issssss", $userId, $nombre, $nickname, $fechaNacimiento, $imagen, $telefono, $entidadOrganizadora);
        $stmt -> execute();
        $stmt -> close();
        $con -> close();   
        header('HTTP/1.1 201 OK');       
    }else{
        header('HTTP/1.1 400 Bad Request');
        echo 'Error al preparar la consulta: ' . $con->error;
        exit();
    
    }
}

?>