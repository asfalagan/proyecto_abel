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
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $formPasswd = $data['passwd'];
    $email = $data['email'];
    // echo $formPasswd;
    // echo $email;
    // $formPasswd = '#Aa1234#';
    // $email = 'prueba@email.com';

    $con = new Conexion();
    if(isset($formPasswd) && isset($email)){
    
        $salt;
        $jwtkey;
        $nombre;
        $nickname;
        $is_admin;  
        $passwd;
        $imagen;
        $fechaNacimiento;
        $user_id;
        
        try {
            $sql = "SELECT id, nombre, nickname, email, passwd, salt,  imagen, fecha_nacimiento, email_is_admin(?) FROM usuario WHERE email = ?";
            if($stmt = $con -> prepare($sql)){
                $stmt -> bind_param("ss", $email, $email);
                $stmt -> execute();
                $stmt -> bind_result($user_id, $nombre, $nickname, $email, $passwd, $salt, $imagen, $fechaNacimiento, 
            
            
            );               
                $stmt -> fetch();
                $con -> close();
                
            }else{
                exit();
            }
            //compruebo que la contraseña sea correcta
            $formPasswd = hash('sha256', $formPasswd.$salt);

            if($formPasswd === $passwd){
            //compruebo si el usuario es administrador
            //construyo el JWT
                // $header = '{"alg":"HS256","typ":"JWT"}';
                // $payload = '{"email":"'.$email.'","nombre":"'.$nombre.'","nickname":"'.$nickname.'","imagen":"'.$imagen.'","fechaNacimiento":"'.$fechaNacimiento.'"}';
                // $header = base64_encode($header);
                // $payload = base64_encode($payload);
                // $signature = hash_hmac('sha256', $header.".".$payload, $jwtkey, true);
                // $signature = base64_encode($signature);
                // $jwt = $header.".".$payload.".".$signature;
                // header("HTTP/1.1 201 OK	");
                
                // echo json_encode($jwt);
                $mtime = time() * 1000;
                $exp = $mtime + 3600;
                $data = [
                    'userId' => $user_id,
                    'userNickname' => $nickname,
                    'isAdmin' => $is_admin,
                ];
                $payload = [
                    'exp' => $exp,
                    'userData' => $data,
                ];
                //echo 'ES ADMIN: '.$is_admin.'<br>'; -> FUNCIONA CORRECTAMENTE
                $codificada = JWT::encode($payload, $jwtkey, 'HS256');
                //$decodificada = JWT::decode($codificada,new key($jwtkey, 'HS256')); //-> Decodificia el JWT
                //echo $codificada;
                // echo '<br>';
                // echo '<pre>';
                // var_dump($decodificada);
                // echo '</pre>';
                // echo '<br>';
                //echo json_encode($codificada);
                header("HTTP/1.1 201 Inicio de sesion correcto");
                //envio $codificada como cuerpo de la solicitud HTTP
                echo json_encode($codificada);

                exit();
            }else{
                header("HTTP/1.1 401 Combinacion incorrecta de email y contraseña");
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
    }
    
    
}
header("HTTP/1.1 400 Bad Request");
exit();

?>