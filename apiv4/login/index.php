<?php

##recibe un correo y contraseña 
##devuelve:
##200 -> JWT
##401 -> Combinacion incorrecta de email y contraseña
##400 -> BadRequest
##usuario de pruebas prueba@email.com #Aa1234#
require_once '../clases/conexion.php';
require_once '../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Authorization, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $formPasswd = $_POST['password'];
    $email = $_POST['email'];
    
    if(isset($formPasswd) && isset($email)){   
        $salt;
        $jwtkey;
        $nombre;
        $nickname;
        $isAdmin;  
        $passwd;
        $imagen;
        $fechaNacimiento;
        $userId;
        $registroCompletado;
        // try {
            $con = new Conexion();
            $sql = "SELECT id, nombre, nickname, email, passwd, salt,  imagen, fecha_nacimiento, email_is_admin(?) FROM usuario WHERE email = ?";
            if($stmt = $con -> prepare($sql)){
                $stmt -> bind_param("ss", $email, $email);
                $stmt -> execute();
                $stmt -> bind_result($userId, $nombre, $nickname, $email, $passwd, $salt, $imagen, $fechaNacimiento, $isAdmin);
                $stmt -> fetch();
                $stmt -> close();
                $con -> close();
                if(!isset($userId)){
                    header("HTTP/1.1 401 Combinacion incorrecta de email y contraseña");
                    exit();
                }
            }else{
                header("HTTP/1.1 400 Bad Request");
                exit();
            }
            // echo 'El id con el que lo intento es: '.$userId.'<br>';
            $con = new Conexion();
            $sql = "SELECT comprobar_registro(?)";
            if($stmt = $con -> prepare($sql)){
                $stmt -> bind_param("i", $userId);
                $stmt -> execute();
                $stmt -> bind_result($registroCompletado);
                $stmt -> fetch();
                $stmt -> close();
                $con -> close();
            }else{
                header("HTTP/1.1 400 Bad Request -> fallo en la segunda conexion DB");
                exit();
            }
            // echo 'El registro completado es: '.$registroCompletado.'<br>';
            if(!isset($registroCompletado) || $registroCompletado == NULL || $registroCompletado < 1){
                $pld = [
                    'exp' => time() * 1000 + 3600,
                    'userData' => [
                        'userId' => $userId,
                        'isAdmin' => $isAdmin,
                        'completado' => false,
                    ]
                ];
                $token = JWT::encode($pld, $jwtkey, 'HS256');
                header("HTTP/1.1 201 Usuario no ha completado el registro");
                echo json_encode($token);
                exit();
            }
            $formPasswd = hash('sha256', $formPasswd.$salt);
            if($formPasswd === $passwd){
                $mtime = time() * 1000;
                $exp = $mtime + 3600;
                $data = [
                    'userId' => $userId,
                    'userNickname' => $nickname,
                    'isAdmin' => $isAdmin,
                    'userEmail' => $email,
                    'userNombre' => $nombre,
                    'userImagen' => $imagen,
                    'userFechaNacimiento' => $fechaNacimiento,
                    'completado' => true,
                ];
                if($isAdmin){
                    $telefono;
                    $entidad_organizadora;
                    $con = new Conexion();
                    $sql = "SELECT telefono, entidad_organizadora FROM usuario_organizador WHERE id_usuario = ?";
                    if($stmt = $con -> prepare($sql)){
                        $stmt -> bind_param("i", $userId);
                        $stmt -> execute();
                        $stmt -> bind_result($telefono, $entidad_organizadora);
                        $stmt -> fetch();
                        $stmt -> close();
                        $con -> close();
                    }else{
                        header("HTTP/1.1 400 Bad Request -> fallo en la tercera conexion DB");
                        exit();
                    }
                    $data['telefono'] = $telefono;
                    $data['entidadOrganizadora'] = $entidad_organizadora;
                }
                $payload = [
                    'exp' => time() * 1000 + 3600,
                    'userData' => $data,
                ];
                $jwtEncode = JWT::encode($payload, $jwtkey, 'HS256');          
                header("HTTP/1.1 201 Inicio de sesion correcto");
                echo json_encode($jwtEncode);
                exit();
            }else{
                header("HTTP/1.1 401 Combinacion incorrecta de email y contraseña");
                exit();
            }
        // } catch (mysqli_sql_exception $e) {
        //     header("HTTP/1.1 404 Not Found -> fallo en la primera conexion DB");
        //     exit();
        // }
    }   
}
header("HTTP/1.1 400 Bad Request");
echo json_encode("Error en la peticion necesdito metodo post");
exit();

?>