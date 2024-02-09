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
    echo 'usuarioId: '.$usuarioId.'<br>';
    //gestion del cartel
     if(isset($_FILES['cartel'])){// UPLOAD_ERR_OK da 0; no hay errores
        $cartel = $_FILES['cartel'];
        $rutaDestinoCartel = '../../../img/carteles/';
        #muevo el archivo a la carpeta de destino
        $nombreCartel = basename($cartel['name']);
        #de esta forma nos aseguramos que cada imagen subida tiene url unica
        $nombreCartel = uniqid().date("Ymd").$nombreCartel;
        $rutaDestinoCartel .= $nombreCartel;
        move_uploaded_file($cartel["tmp_name"], $rutaDestinoCartel);
    }
    //gestion del reglamento
    if(isset($_FILES['reglamento'])){// UPLOAD_ERR_OK da 0; no hay errores
        $reglamento = $_FILES['reglamento'];
        $rutaDestinoReglamento = '../../../ficheros/reglamentos/';
        #muevo el archivo a la carpeta de destino
        $nombreReglamento = basename($reglamento['name']);
        #de esta forma nos aseguramos que cada imagen subida tiene url unica
        $nombreReglamento = uniqid().date("Ymd").$nombreReglamento;
        $rutaDestinoReglamento .= $nombreReglamento;
        move_uploaded_file($reglamento["tmp_name"], $rutaDestinoReglamento);
    }
    // $rutaDestinoCartel
    // $rutaDestinoReglamento
    header("HTTP/1.1 201 Registro de evento correcto");
    var_dump($_REQUEST);
    if(isset($_POST['eventoid'])){
        $eventoId = $_POST['eventoid'];
    }
    $conexion = new Conexion;
    if($conexion->connect_error){
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    $sql = "INSERT INTO evento (id_organizador, nombre, localidad, provincia, fecha_inicio, fecha_fin, web, url_reglamento, url_cartel) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('issssssss', $usuarioId, $_POST['nombre'], $_POST['localidad'], $_POST['provincia'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['web'], $rutaDestinoReglamento, $rutaDestinoCartel);
    $stmt->execute();
    $stmt->close();
    $conexion->close();
    header("HTTP/1.1 201 Registro de evento correcto");
    var_dump($_REQUEST);
    
    }else{
        header("HTTP/1.1 400 Peticion no permitida");
 }
?>