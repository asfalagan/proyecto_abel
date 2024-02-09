<?php
//FUNCIONES DML
function getUserData($userId){
    // include './conexion.php';
    ##devuelve un array con todos los datos o -1 si no encuentra nada
    $nombre;
    $nickname;
    $email;
    $imagen;
    $fechaNacimiento;
    $isAdmin;
    $tel;
    $entidadOrganizadora;

    $con = new Conexion();
    if($con -> connect_errno){
        return -1;
        exit();
    }
    $query = 'SELECT nombre, nickname, email, imagen, fecha_nacimiento, id_is_admin(?) FROM usuario WHERE id = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('ii', $userId, $userId);
        $stmt -> execute();
        $stmt -> bind_result($nombre, $nickname, $email, $imagen, $fechaNacimiento, $isAdmin);
        $peliculas = [];
        while($stmt -> fetch()){
           $data = [
            'userId' => $userId,
            'nombre' => $nombre,
            'nickname' => $nickname,
            'email' => $email,
            'imagen' => $imagen,
            'fechaNacimiento' => $fechaNacimiento,
            'isAdmin' => $isAdmin,
           ];
        }
        $stmt -> close();
    }
    if($data['isAdmin'] == 1){
        $query = 'SELECT telefono, entidad_organizadora FROM usuario_organizador WHERE id_usuario = ?';
        if($stmt = $con -> prepare($query)){
            $stmt -> bind_param('i', $userId);
            $stmt -> execute();
            $stmt -> bind_result($tel, $entidadOrganizadora);
            while($stmt -> fetch()){
                $data['entidadOrganizadora'] = $entidadOrganizadora;
                $data['tel'] = $tel;
            }
            $stmt -> close();
        }
    }
    $con -> close();

    return count($data) == 0 ? -1: $data;
}
function updateUserdata($userId, $userData){
    // include './conexion.php';
    //pido los datos del usuario
    $userOldData = getUserData($userId);
    //compruebo los datos que quiere cambiar el usuario
    $userNewData = array_merge($userOldData, $userData);
    //actualizo los datos del usuario 
    $con = new Conexion();
    if($con -> connect_errno){
        return -1;
        exit();
    }
    //si admin actualizo los datos de admin
    if($userNewData['isAdmin'] == 1){
        $query = 'UPDATE usuario_organizador SET telefono = ?, entidad_organizadora = ? WHERE id_usuario = ?';
        if($stmt = $con -> prepare($query)){
            $stmt -> bind_param('ssi', $userNewData['tel'], $userNewData['entidadOrganizadora'], $userId);
            if($stmt -> execute()){
                $stmt -> close();
            }else{
                $stmt -> close();
                $con -> close();
                return -1;
            }
        }else{
            $con -> close();
            return -1;
        }
    }
    //actualizo los datos del usuario
    $query = 'UPDATE usuario SET nombre = ?, nickname = ?, email = ?, imagen = ?, fecha_nacimiento = ? WHERE id = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('sssssi', $userNewData['nombre'], $userNewData['nickname'], $userNewData['email'], $userNewData['imagen'], $userNewData['fechaNacimiento'], $userId);
        if($stmt -> execute()){
            $stmt -> close();
            $con -> close();
        }else{
            $stmt -> close();
            $con -> close();
            return -1;
        }
    }else{
        $con -> close();
        return -1;
    }
    return $userNewData;
}
function getEvents(){
    
}
function getRaces($eventId){
    
}
?>