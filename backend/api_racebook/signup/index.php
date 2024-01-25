<?php

include '../clases/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $passwd = $data['password'];
    $email = $data['email'];
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
    
        $sql = "INSERT INTO usuario (email, passwd, salt) VALUES (?, ?, ?)";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("sss", $email, $cryptedPasswd, $salt);
            if ($stmt->execute()) {
                $stmt->close();
                $con->close();
                enviarRespuesta(200, "Usuario registrado correctamente");
            } else {
                $stmt->close();
                $con->close();
                enviarRespuesta(400, "Algo falló en la inserción del usuario");
            }
        } else {
            $con->close();
            enviarRespuesta(400, "No se ha podido registrar el usuario");
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