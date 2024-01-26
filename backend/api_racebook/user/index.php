<?php

##recibe un correo y contraseña 
##devuelve:
##200 -> JWT
##401 -> Combinacion incorrecta de email y contraseña
##400 -> BadRequest
##usuario de pruebas prueba@email.com #Aa1234#
require_once '../clases/conexion.php';
require_once '../../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //verifico que la cabecra authorization exista
    if(!isset(apache_request_headers()['Authorization'])){//https://www.php.net/manual/en/function.apache-request-headers
        header('HTTP/1.1 402 BAD REQUEST -> Authorization header not found');
        exit();
    }
    //guardo el token JWT en una variable
    //verifico si comienza con 'Bearer '
    if (strpos($authorizationHeader, 'Bearer ') === 0) {
        //obtengo el token excluyendo 'Bearer ' del inicio
        $tokenJWT = substr($authorizationHeader, 7);
        //compruebo que sea valido -> creo otro token y lo devuelvo
        try{
            $decodedJWT = JWT::decode($codificada,new key($jwtkey, 'HS256'));// -> Decodifica el JWT
        }catch(Exception $e){
            header('HTTP/1.1 403 BAD REQUEST -> Invalid JWT');
            exit();
        }
        //obtengo el payload del JWT
        $userData = $decodedJWT->data;
        $userId = $userData->id;
        $data = getUserData($userId);
        //comprubo que he recibido los datos del usuario
        if($data == -1){
            header('HTTP/1.1 500 SERVER ERROR -> Error getting user data');
            exit();
        }
        $payload = [
            'exp' => time() + (60*60), // 1 hora
            'user_data' => $data,
        ];
        $jwt = JWT::encode($payload, $jwtkey, 'HS256');
        echo $jwt;
        header("HTTP/1.1 200 All Ok");
    } else {
        // La cabecera 'Authorization' no tiene el formato esperado
        header('HTTP/1.1 403 BAD REQUEST -> Invalid Authorization header format');
    }
    exit();
}else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    //verifico que la cabecra authorization exista
    if(!isset(apache_request_headers()['Authorization'])){//https://www.php.net/manual/en/function.apache-request-headers
        header('HTTP/1.1 402 BAD REQUEST -> Authorization header not found');
        exit();
    }
    //guardo el token JWT en una variable
    //verifico si comienza con 'Bearer '
    if (strpos($authorizationHeader, 'Bearer ') === 0) {
        //obtengo el token excluyendo 'Bearer ' del inicio
        $tokenJWT = substr($authorizationHeader, 7);
        //compruebo que sea valido -> creo otro token y lo devuelvo
        try{
            $decodedJWT = JWT::decode($codificada,new key($jwtkey, 'HS256'));// -> Decodifica el JWT
        }catch(Exception $e){
            header('HTTP/1.1 403 BAD REQUEST -> Invalid JWT');
            exit();
        }
        //obtengo el payload del JWT
        $userData = $decodedJWT->data;
        $userId = $userData->id;
        $newData = updateUserdata($userId, $data);
        //comprubo que he modificado los datos del usuario
        if($data == -1){
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
    } else {
        // La cabecera 'Authorization' no tiene el formato esperado
        header('HTTP/1.1 403 BAD REQUEST -> Invalid Authorization header format');
    }
}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

}else{
    header('HTTP/1.1 400 BAD REQUEST -> Request method not valid');
    exit();
}

?>