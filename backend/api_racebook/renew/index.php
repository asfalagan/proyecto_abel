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
        
    } else {
        // La cabecera 'Authorization' no tiene el formato esperado
        header('HTTP/1.1 403 BAD REQUEST -> Invalid Authorization header format');
    }

    exit();
}else{
    header('HTTP/1.1 400 BAD REQUEST -> Request method not valid');
    exit();
}
##
// if($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $json_data = file_get_contents("php://input");
//     $data = json_decode($json_data, true);
//     $formPasswd = $data['passwd'];
//     $email = $data['email'];
//     // echo $formPasswd;
//     // echo $email;

//     $con = new Conexion();
//     if(isset($formPasswd) && isset($email)){
    
//         $salt;
//         $jwtkey;
//         $nombre;
//         $nickname;
//         $is_admin;  
//         $passwd;
//         $imagen;
//         $fechaNacimiento;
//         $user_id;
        
//         try {
//             $sql = "SELECT id, nombre, nickname, email, passwd, salt,  imagen, fecha_nacimiento, is_admin(?) FROM usuario WHERE email = ?";
//             if($stmt = $con -> prepare($sql)){
//                 $stmt -> bind_param("ss", $email, $email);
//                 $stmt -> execute();
//                 $stmt -> bind_result($user_id, $nombre, $nickname, $email, $passwd, $salt, $imagen, $fechaNacimiento, $is_admin);               
//                 $stmt -> fetch();
//                 $con -> close();
                
//             }else{
//                 exit();
//             }
//             //compruebo que la contraseña sea correcta
//             $formPasswd = hash('sha256', $formPasswd.$salt);

//             if($formPasswd === $passwd){
//             //compruebo si el usuario es administrador
//             //construyo el JWT
//                 // $header = '{"alg":"HS256","typ":"JWT"}';
//                 // $payload = '{"email":"'.$email.'","nombre":"'.$nombre.'","nickname":"'.$nickname.'","imagen":"'.$imagen.'","fechaNacimiento":"'.$fechaNacimiento.'"}';
//                 // $header = base64_encode($header);
//                 // $payload = base64_encode($payload);
//                 // $signature = hash_hmac('sha256', $header.".".$payload, $jwtkey, true);
//                 // $signature = base64_encode($signature);
//                 // $jwt = $header.".".$payload.".".$signature;
//                 // header("HTTP/1.1 201 OK	");
                
//                 // echo json_encode($jwt);
//                 $payload = [
//                     'exp' => time() + 3600,
//                     'user_id' => $user_id,
//                     'user_nick' => $nickname,
//                     'is_admin' => $is_admin,
//                 ];
//                 //echo 'ES ADMIN: '.$is_admin.'<br>'; -> FUNCIONA CORRECTAMENTE
//                 $codificada = JWT::encode($payload, $jwtkey, 'HS256');
//                 // $decodificada = JWT::decode($codificada,new key($jwtkey, 'HS256')); -> Decodificia el JWT
//                 // echo $codificada;
//                 // echo '<br>';
//                 // echo '<pre>';
//                 // var_dump($decodificada);
//                 // echo '</pre>';
//                 // echo '<br>';
//                 echo json_encode($codificada);
//                 header("HTTP/1.1 201 Inicio de sesion correcto");
//                 exit();
//             }else{
//                 header("HTTP/1.1 401 Combinacion incorrecta de email y contraseña");
//                 exit();
//             }
//         } catch (mysqli_sql_exception $e) {
//             header("HTTP/1.1 404 Not Found");
//             exit();
//         }
//     }
    
    
// }
// header("HTTP/1.1 400 Bad Request");
// exit();

?>