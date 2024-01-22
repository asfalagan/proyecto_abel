<?php
##recibe un correo y contraseña 
##devuelve:
##200 -> JWT
##201 -> Combinacion incorrecta de email y contraseña
##400 -> BadRequest

require_once '../clases/conexion.php';
$con = new Conexion();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['email'])&& isset($_POST['passwd'])){
        try {
            $sql = "SELECT * FROM usuario WHERE email = ?";
            $stmt = $con -> prepare($sql);
            $stmt = $stmt -> bind_param('s', $_POST['email']);
            $result = $con -> execute($stmt);
            $usuarios = $result->fetch_all(MYSQLI_ASSOC);
            $con -> close();
            header("HTTP/1.1 200 OK");
            echo json_encode($usuarios);
        } catch (mysqli_sql_exception $e) {
            header("HTTP/1.1 404 Not Found");
        }
    }
    
    
}
header("HTTP/1.1 400 Bad Request");

?>