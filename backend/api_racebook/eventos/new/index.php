<?php
require_once '../../clases/conexion.php';
require_once '../../../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, eventoid");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
    



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // //gestion del cartel
    //  if($_FILES['cartel']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
    //     $cartel = $_FILES['cartel'];
    //     $rutaDestinoCartel = '../../../img/carteles/';
    //     #muevo el archivo a la carpeta de destino
    //     $nombreCartel = basename($cartel['name']);
    //     #de esta forma nos aseguramos que cada imagen subida tiene url unica
    //     $nombreCartel = uniqid().date("Ymd").$nombreCartel;
    //     $rutaDestinoCartel .= $nombreCartel;
    //     move_uploaded_file($cartel["tmp_name"], $rutaDestinoCartel);
    // }
    // //gestion del reglamento
    // if($_FILES['reglamento']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
    //     $reglamento = $_FILES['reglamento'];
    //     $rutaDestinoReglamento = '../../../ficheros/reglamentos/';
    //     #muevo el archivo a la carpeta de destino
    //     $nombreReglamento = basename($reglamento['name']);
    //     #de esta forma nos aseguramos que cada imagen subida tiene url unica
    //     $nombreReglamento = uniqid().date("Ymd").$nombreReglamento;
    //     $rutaDestinoReglamento .= $nombreReglamento;
    //     move_uploaded_file($reglamento["tmp_name"], $rutaDestinoReglamento);
    // }
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $nombre = $data['nombre'];
    $fechaInicio = $data['fechaInicio'];
    $fechaFin = $data['fechaFin'];
    $localidad = $data['localidad'];
    $provincia = $data['provincia'];
    $webEvento = $data['webEvento'];
    $userId = $data['userId'];
    //$rutaDestinoCartel
    //$rutaDestinoReglamento
    $idEvento;
    $conexion = new Conexion;
    if($conexion->connect_error){
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    $sql = "SELECT insertar_evento (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $localidad, $provincia, $fechaInicio, $fechaFin, $webEvento, $userId);
    $stmt->execute();
    $stmt->bind_result($idEvento);
    $stmt->fetch();
    $stmt->close();
    $conexion->close();
    
    if($idEvento != null){
        header("HTTP/1.1 201 Registro de evento correcto");
        echo json_encode($idEvento);
    }else{
        header("HTTP/1.1 400 Bad Request");
    }
}else{
    header("HTTP/1.1 400 Peticion no permitida");
}
?>