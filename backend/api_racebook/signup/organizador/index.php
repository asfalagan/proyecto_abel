<?php
//esta llamada completa el registro de un usuario organizador
require_once '../clases/conexion.php';
require_once '../../../vendor/autoload.php';
require_once '../clases/dml.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    //verifico que la cabecra authorization exista
    if(!isset(apache_request_headers()['Authorization'])){//https://www.php.net/manual/en/function.apache-request-headers
    header('HTTP/1.1 402 BAD REQUEST -> Authorization header not found');
    exit();
    }
    $allHeaders = getallheaders();
    //var_dump($allHeaders) ;
    $authToken = $allHeaders['Authorization'];
    //compruebo que sea valido
    try{
        $decodedJWT = JWT::decode($authToken,new key($jwtkey, 'HS256'));// -> Decodifica el JWT
    }catch(Exception $e){
        header('HTTP/1.1 403 BAD REQUEST -> Invalid JWT');
        exit();
    }
    //guardo el Objeto correspondiente a los datos del usuario del Payload
    //en una variable que recorrere para pasar a un array
    $userDataStdObject = $decodedJWT->user_data;
    //recupero los datos del usuario del JWT payload y los guardo en el array userData
    $userData = [];
    foreach($userDataStdObject as $key => $value){
        $userData[$key] = $value;
    }
    //ahora $userData contiene todos los datos del usuario que contenia el JWT de la cabecera
    //voy a obtener el cuerpo de la solicitud
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    //$data es un array que contiene los datos que voy a meter en la DB
    //voy a obtener el id del usuario del $userData[]
    $userId = $userData['user_id'];

    //https://chat.openai.com/share/b33b1482-60fb-4676-8f8b-85194fe0d278
    echo $json_data;
    echo '<br> ^ Data segun llega en el request <br>';
    var_dump($decodedJWT);
    echo '<br> ^ decodedJWT <br>';
    var_dump($decodedJWT->user_data);
    echo '<br> ^ decodedJWT -> user_data <br>';
    var_dump($data);
    echo '<br> ^ decoded_json_data<br>';
    // echo json_decode($data, true);
    // echo '<br> ^ redecoded_json_data <br>'; -> ESTO LANZA ERROR PORQUE RECIBE UN ARRAY NO UN JSON 
    echo $userId;
    echo '<br> ^ id usuario <br>';
    echo '<br>';
    $newData = updateUserdata($userId, $data);
    //comprubo que he modificado los datos del usuario
    if($newData == -1){
        header('HTTP/1.1 500 SERVER ERROR -> Error updating user data');
        exit();
    }
    $payload = [
        'exp' => time() + (60*60), // 1 hora
        'user_data' => $newData,
    ];
    $jwt = JWT::encode($payload, $jwtkey, 'HS256');
    echo $jwt;
    header("HTTP/1.1 200 All Ok");
    exit();
}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

}else{
    header('HTTP/1.1 400 BAD REQUEST -> Request method not valid');
    exit();
}

?>