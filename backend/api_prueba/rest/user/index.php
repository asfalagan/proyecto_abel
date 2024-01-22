<?php
    require_once '../clases/conexion.php';
    $con = new Conexion();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id_usuario = $_POST['id'];
        try {
            $sql = "SELECT * FROM usuarios WHERE id = $id_usuario";
            
            $result = $con -> query($sql);
            $usuarios = $result->fetch_all(MYSQLI_ASSOC);
            $con -> close();
            header("HTTP/1.1 200 OK");
            echo json_encode($usuarios);
        } catch (mysqli_sql_exception $e) {
            header("HTTP/1.1 404 Not Found");
        }
        exit;
    }
    header("HTTP/1.1 400 Bad Request");
?>