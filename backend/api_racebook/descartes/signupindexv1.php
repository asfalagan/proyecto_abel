<?php
//esta llamada funciona si no recibo un formData y recibo un json
require_once '../clases/conexion.php';
require_once '../../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $isAdmin = $data['isAdmin'];
    $passwd = $data['password'];
    $email = $data['email'];
    // $passwd = '#Aa1234#';
    // $email = 'prueba@email.com';
    $salt = base64_encode(random_bytes(6));
    $cryptedPasswd = hash('sha256', $passwd . $salt);

    $emailTest;
    $conTest = new Conexion();
    if ($conTest->connect_error) {
        enviarRespuesta(500, "Error en la conexión a la base de datos");
        exit();
    }
    if(!isset($email)||!isset($passwd)){enviarRespuesta(400, "Error Al Registrar usuario COD 1");}
    $sql = "SELECT email FROM usuario WHERE email = ?";
    $stmt = $conTest->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($emailTest);
    $stmt->fetch();
    $stmt->close();
    $conTest->close();

    if ($emailTest == $email) {
        enviarRespuesta(402, "Email ya registrado");
    
    } else {
        $con = new Conexion();
        if ($con->connect_error) {
            enviarRespuesta(500, "Error en la conexión a la base de datos");
            exit();
        }
        if($isAdmin){
            $sql = "CALL insertar_administrador(?, ?, ?)";
        }else{
            $sql = "INSERT INTO usuario (email, passwd, salt) VALUES (?, ?, ?)";
        }
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("sss", $email, $cryptedPasswd, $salt);
            if ($stmt->execute()) {
                $stmt->close();
                $con->close();

                $sql  = "SELECT id FROM usuario WHERE email = ?";
                $con = new Conexion();
                if ($con->connect_error) {
                    enviarRespuesta(500, "Error en la conexión a la base de datos");
                    exit();
                }
                if($stmt = $con->prepare($sql)){
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->bind_result($idUsuario);
                    $stmt->fetch();
                    $stmt->close();
                    $con->close();
                    $mtime = time() * 1000;
                    $exp = $mtime + 3600;
                    echo 'Aqui va el id usuario: ';
                    echo $idUsuario;
                    echo '<br>';
                    $data = [
                        'userId' => $idUsuario,
                        'isAdmin' => $isAdmin,
                    ];
                    $payload = [
                        'exp' => $exp,
                        'userData' => $data,
                    ];
                    $jwt = JWT::encode($payload, $jwtkey, 'HS256');
                    header("HTTP/1.1 201 Inicio de sesion correcto");
                    echo json_encode($jwt);
                // enviarRespuesta(200, "Usuario registrado correctamente");
                //construyo un JWT que contiene el id_usuario del nuevo usuario para poder completar el registro
                } else {
                    $stmt->close();
                    $con->close();
                    header("HTTP/1.1 400 Bad Request");
                }
            } else {
                $con->close();
                header("HTTP/1.1 400 Bad Request");
            }
        }
    }
} else {
    enviarRespuesta(400, "Bad Request COD 1");
}
function enviarRespuesta($codigo, $mensaje)
{
    http_response_code($codigo);
    header('Content-Type: application/json');
    echo json_encode(array('mensaje' => $mensaje));
}
?>