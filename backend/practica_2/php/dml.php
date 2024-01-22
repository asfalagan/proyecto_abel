<?php
##aqui escribimos las funciones correspondientes a operaciones CRUD en la base de datos
##las funciones reciben $_POST como parametro -> $DATOS es un clon de $_POST 
## debo incluir include '../keys/datos.php'; en cada funcion o pasarle las variables como parametro

##peliculas
function get_id_pelicula($titulo, $director){//si la pelicula no existe devuelve null
    include '../keys/datos.php';
    $id_pelicula = null;
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    if($con -> connect_errno){
        exit();
    }
    $query = 'SELECT id FROM peliculas WHERE titulo = ? AND director = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('ss', $titulo, $director);
        $stmt -> execute();
        $stmt -> bind_result($id_pelicula);
        $stmt -> fetch();
        $stmt -> close();
    }
    $con -> close();

    return $id_pelicula;
}
function insert_pelicula($DATOS, $FILES){
    include '../keys/datos.php';
    ##quito los espacios que pueda haber a derecha e izquierda de las variables
    $DATOS['titulo'] = trim($DATOS['titulo']);
    $DATOS['director'] = trim($DATOS['director']);
    $DATOS['genero'] = trim($DATOS['genero']);
    $DATOS['pais'] = trim($DATOS['pais']);
    $url_cartel;
    $ruta_destino;
    ##miro a ver si tengo ya la pelicula
    if(!exist_pelicula($DATOS['titulo'], $DATOS['director'])){
        ##manejo del cartel: 
        if($FILES['cartel']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
            $cartel = $FILES['cartel'];
            $ruta_destino = '../img/';
            #si la ruta de destino no existe -> la creo
            if(!is_dir($ruta_destino)){
                mkdir($ruta_destino, 0777, true);//ojo con los permisos que das aqui
            }
            #muevo el archivo a la carpeta de destino
            $nombre_cartel = basename($cartel['name']);
            #de esta forma nos aseguramos que cada imagen subida tiene url unica
            $nombre_cartel = uniqid().date("Ymd").$nombre_cartel;
            $ruta_destino .= $nombre_cartel;
            move_uploaded_file($cartel["tmp_name"], $ruta_destino);

        } 
        if(isset($ruta_destino)){
            $url_cartel = $ruta_destino;
        }else{
            $url_cartel = 'No disponemos de imagen para este registro';
        }
       
        ##creo la conexion
        $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
        ##verfico conexion
        if ($con -> connect_errno){
            exit();
        }
        $query = 'INSERT INTO peliculas (titulo, director, genero, pais, fecha, cartel) VALUES (?, ?, ?, ?, ?, ?)';
        ##creo una sentencia preparada -> en caso de error devolveria false 
        ##https://www.php.net/manual/es/mysqli.prepare.php
        if($stmt = $con -> prepare($query)){
            ##ligo parametros a marcadores
            $stmt->bind_param('ssssss', $DATOS['titulo'], $DATOS['director'], $DATOS['genero'], $DATOS['pais'], $DATOS['fecha'], $url_cartel );
            ##ejecuto la consulta
            $stmt -> execute();
            $stmt -> close();
        }
        $con -> close();
        ##compruebo que ahora si existe la pelicula
        return exist_pelicula($DATOS['titulo'], $DATOS['director']);
    }else{
        return false;
    }
    
}
function update_pelicula($DATOS, $FILES, $pelicula){
    include '../keys/datos.php';
    ##quito los espacios que pueda haber a derecha e izquierda de las variables
    $DATOS['titulo'] = trim($DATOS['titulo']);
    $DATOS['director'] = trim($DATOS['director']);
    $DATOS['genero'] = trim($DATOS['genero']);
    $DATOS['pais'] = trim($DATOS['pais']);
    $url_cartel = $pelicula['cartel'];
    ##compruebo que campos estan seteados en datos; los que no esten seteados le doy los valores del campo actual en la db
    if(($DATOS['titulo']) == ''){
        $DATOS['titulo'] = $pelicula['titulo'];
    }
    if(($DATOS['director']) == ''){
        $DATOS['director'] = $pelicula['director'];
    }
    if(($DATOS['genero']) == ''){
        $DATOS['genero'] = $pelicula['genero'];
    }
    if(($DATOS['pais']) == ''){
        $DATOS['pais'] = $pelicula['pais'];
    }
    if(($DATOS['fecha']) == '' || $DATOS['fecha'] == '0000-00-00'){
        if($pelicula['fecha'] != '' && $pelicula['fecha'] != '0000-00-00'){
            $DATOS['fecha'] = $pelicula['fecha'];
        }else{
            $fecha_actual = date('Y-m-d');
            $DATOS['fecha'] = $fecha_actual;
        }      
    }
    if(($FILES['cartel']) == ''){
        $FILES['cartel'] = $url_cartel;
    }else{
         ##manejo del cartel: -> si hay un error la url del cartel sera el valor anterior a ser modificado
         if($FILES['cartel']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
            $cartel = $FILES['cartel'];
            $ruta_destino = '../img/';
            #si la ruta de destino no existe -> la creo
            if(!is_dir($ruta_destino)){
                mkdir($ruta_destino, 0777, true);//ojo con los permisos que das aqui
            }
            #de esta forma nos aseguramos que cada imagen subida tiene url unica
            $nombre_cartel = uniqid().date("Ymd").basename($cartel['name']);
            $ruta_destino .= $nombre_cartel;
            #muevo el archivo a la carpeta de destino
            if(move_uploaded_file($cartel["tmp_name"], $ruta_destino)){
                ##borro el archivo correspondiente al cartel viejo: 
                if (file_exists($pelicula['cartel'])){
                    unlink($pelicula['cartel']);
                }
                $url_cartel = $ruta_destino;
            }
        } 
    }
    ##creo la conexion
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    ##verfico conexion
    if ($con -> connect_errno){
        exit();
    }
    $query = 'UPDATE peliculas SET titulo = ?, director = ?, genero = ?, pais = ?, fecha = ?, cartel = ? WHERE id = '.$pelicula['id'].'';
    ##creo una sentencia preparada -> en caso de error devolveria false 
    ##https://www.php.net/manual/es/mysqli.prepare.php 
    if($stmt = $con -> prepare($query)){
        ##ligo parametros a marcadores
        $stmt->bind_param('ssssss', $DATOS['titulo'], $DATOS['director'], $DATOS['genero'], $DATOS['pais'], $DATOS['fecha'], $url_cartel );
        ##ejecuto la consulta
        $stmt -> execute();
        $stmt -> close();
        $con -> close();
        return true;
    }
    $con -> close();
    return false;
}
function delete_pelicula($id_pelicula){
    include '../keys/datos.php';
    ##creo la conexion
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    ##verfico conexion
    if ($con -> connect_errno){
        return false;
    }
    $query = 'DELETE FROM peliculas WHERE id = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('i', $id_pelicula);
        if($stmt -> execute()){
            $stmt -> close();
            $con -> close();
            return true;
        }else{       
            $stmt -> close();
            $con -> close();
            return false;
        }
    }else{
        $stmt -> close();
        $con -> close();
        return false;
    }
}
function get_peliculas(){
    include '../keys/datos.php';
    ##devuelve un array con todas las peliculas o -1 si no hay nada
    $id_pelicula;
    $titulo;
    $director;
    $genero;
    $fecha;
    $pais;
    $cartel;
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    if($con -> connect_errno){
        exit();
    }
    $query = 'SELECT * FROM peliculas';
    if($stmt = $con -> prepare($query)){
        $stmt -> execute();
        $stmt -> bind_result($id_pelicula, $titulo, $director, $genero, $pais, $fecha, $cartel);
        $peliculas = [];
        while($stmt -> fetch()){
           $pelicula = [
            'id' => $id_pelicula,
            'titulo' => $titulo,
            'director' => $director,
            'genero' => $genero,
            'pais' => $pais,
            'fecha' => $fecha,
            'cartel' => $cartel,
           ];
           if($pelicula['cartel'] == ''){
                $pelicula['cartel'] = 'No disponemos de cartel';
           }
           array_push($peliculas, $pelicula);
        }
        $stmt -> close();
    }
    $con -> close();

    return count($peliculas) == 0 ? -1: $peliculas;
}
function exist_pelicula($titulo, $director){
    include '../keys/datos.php';
    $id_pelicula = get_id_pelicula($titulo, $director);
    if($id_pelicula){
        return true;
    }else{
        return false;
    }
}
function get_pelicula($id_pelicula){
    include '../keys/datos.php';
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    if($con -> connect_errno){
        exit();
    }
    $titulo;
    $director;
    $genero;
    $fecha;
    $pais;
    $cartel;
    $query = 'SELECT * FROM peliculas WHERE id = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('i', $id_pelicula);
        $stmt -> execute();
        $stmt -> bind_result($id_pelicula, $titulo, $director, $genero, $pais, $fecha, $cartel);
        $stmt -> fetch();
        $stmt -> close();
    }
    $con -> close();
    $pelicula = [
        'id' => $id_pelicula,
        'titulo' => $titulo,
        'director' => $director,
        'genero' => $genero,
        'pais' => $pais,
        'fecha' => $fecha,
        'cartel' => $cartel,
    ];
    if($pelicula['cartel'] == ''){
        $pelicula['cartel'] = 'No disponemos de cartel';
    }

   

    return $pelicula;
    
}
##actores
function get_id_actor($nombre, $apellidos){//si el actor no existe devuelve null
    include '../keys/datos.php';
    $id_actor = null;
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    if($con -> connect_errno){
        exit();
    }
    $query = 'SELECT id FROM actores WHERE nombre = ? AND apellidos = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('ss', $nombre, $apellidos);
        $stmt -> execute();
        $stmt -> bind_result($id_actor);
        $stmt -> fetch();
        $stmt -> close();
    }
    $con -> close();

    return $id_actor;
}
function insert_actor($DATOS, $FILES){
    include '../keys/datos.php';
    ##quito los espacios que pueda haber a derecha e izquierda de las variables
    $DATOS['nombre'] = trim($DATOS['nombre']);
    $DATOS['apellidos'] = trim($DATOS['apellidos']);
    $url_foto;
    $ruta_destino;
    ##miro a ver si tengo ya al actor
    if(!exist_actor($DATOS['nombre'], $DATOS['apellidos'])){
        ##manejo de la foto: 
        if($FILES['foto']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
            $foto = $FILES['foto'];
            $ruta_destino = '../img/';
            #si la ruta de destino no existe -> la creo
            if(!is_dir($ruta_destino)){
                mkdir($ruta_destino, 0777, true);//ojo con los permisos que das aqui
            }
            #de esta forma nos aseguramos que cada imagen subida tiene url unica
            $nombre_foto = uniqid().date("Ymd").basename($foto['name']);
            $ruta_destino .= $nombre_foto;
            move_uploaded_file($foto["tmp_name"], $ruta_destino);

        } 
        if(isset($ruta_destino)){
            $url_foto = $ruta_destino;
        }else{
            $url_foto = 'No disponemos de imagen para este registro';
        }
       
        ##creo la conexion
        $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
        ##verfico conexion
        if ($con -> connect_errno){
            exit();
        }
        $query = 'INSERT INTO actores (nombre, apellidos, foto) VALUES (?, ?, ?)';
        ##creo una sentencia preparada -> en caso de error devolveria false 
        ##https://www.php.net/manual/es/mysqli.prepare.php
        if($stmt = $con -> prepare($query)){
            ##ligo parametros a marcadores
            $stmt->bind_param('sss', $DATOS['nombre'], $DATOS['apellidos'], $url_foto );
            ##ejecuto la consulta
            $stmt -> execute();
            $stmt -> close();
        }
        $con -> close();
        ##compruebo que ahora si existe la pelicula
        return exist_actor($DATOS['nombre'], $DATOS['apellidos']);
    }else{
        return false;
    }
    
}
function update_actor($DATOS, $FILES, $actor){
    include '../keys/datos.php';
    ##quito los espacios que pueda haber a derecha e izquierda de las variables
    $DATOS['nombre'] = trim($DATOS['nombre']);
    $DATOS['apellidos'] = trim($DATOS['apellidos']);
    $url_foto = $actor['foto'];
    ##compruebo que campos estan seteados en datos; los que no esten seteados le doy los valores del campo actual en la db
    if(($DATOS['nombre']) == ''){
        $DATOS['nombre'] = $actor['nombre'];
    }
    if(($DATOS['apellidos']) == ''){
        $DATOS['apellidos'] = $actor['apellidos'];
    }
    if(($FILES['foto']) == ''){
        $FILES['foto'] = $url_foto;
    }else{
         ##manejo de la foto: -> si hay un error la url de la foto sera el valor anterior a ser modificado
         if($FILES['foto']['error'] == UPLOAD_ERR_OK){// UPLOAD_ERR_OK da 0; no hay errores
            $foto = $FILES['foto'];
            $ruta_destino = '../img/';
            #si la ruta de destino no existe -> la creo
            if(!is_dir($ruta_destino)){
                mkdir($ruta_destino, 0777, true);//ojo con los permisos que das aqui
            }
            #de esta forma nos aseguramos que cada imagen subida tiene url unica
            $nombre_foto = uniqid().date("Ymd").basename($foto['name']);
            $ruta_destino .= $nombre_foto;
            #muevo el archivo a la carpeta de destino
            if(move_uploaded_file($foto["tmp_name"], $ruta_destino)){
                ##borro el archivo correspondiente al cartel viejo: 
                if (file_exists($actor['foto'])){
                    unlink($actor['foto']);
                }
                $url_foto = $ruta_destino;
            }
        } 
    }
    ##creo la conexion
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    ##verfico conexion
    if ($con -> connect_errno){
        exit();
    }
    $query = 'UPDATE actores SET nombre = ?, apellidos = ?, foto = ? WHERE id = '.$actor['id'].'';
    ##creo una sentencia preparada -> en caso de error devolveria false 
    ##https://www.php.net/manual/es/mysqli.prepare.php 
    if($stmt = $con -> prepare($query)){
        ##ligo parametros a marcadores
        $stmt->bind_param('sss', $DATOS['nombre'], $DATOS['apellidos'], $url_foto);
        ##ejecuto la consulta
        $stmt -> execute();
        $stmt -> close();
        $con -> close();
        return true;
    }
    $con -> close();
    return false;
}
function delete_actor($id_actor){

    include '../keys/datos.php';
    ##creo la conexion
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    ##verfico conexion
    if ($con -> connect_errno){
        return false;
    }
    $query = 'DELETE FROM actores WHERE id = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('i', $id_actor);
        if($stmt -> execute()){
            $stmt -> close();
            $con -> close();
            return true;
        }else{       
            $stmt -> close();
            $con -> close();
            return false;
        }
    }else{
        $stmt -> close();
        $con -> close();
        return false;
    }
}
function get_actores(){
    include '../keys/datos.php';
    ##devuelve un array con todas las peliculas o -1 si no hay nada
    $actores = [];
    $id;
    $nombre;
    $apellidos;
    $foto;
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    if($con -> connect_errno){
        exit();
    }
    $query = 'SELECT * FROM actores';
    if($stmt = $con -> prepare($query)){
        $stmt -> execute();
        $stmt -> bind_result($id, $nombre, $apellidos, $foto);
        while($stmt -> fetch()){
           $actor = [
            'id' => $id,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'foto' => $foto
           ];
           if($actor['foto'] == ''){
                $actor['foto'] = 'No disponemos de foto';
           }
           array_push($actores, $actor);
        }
        $stmt -> close();
    }
    $con -> close();
    return count($actores) == 0 ? -1: $actores;
}
function exist_actor($nombre, $apellidos){
    include '../keys/datos.php';
    $id = get_id_actor($nombre, $apellidos);
    if($id){
        return true;
    }else{
        return false;
    }
}
function get_actor($id_actor){
    include '../keys/datos.php';
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    if($con -> connect_errno){
        exit();
    }
    $id;
    $nombre;
    $apellidos;
    $foto;
    $query = 'SELECT * FROM actores WHERE id = ?';
    if($stmt = $con -> prepare($query)){
        $stmt -> bind_param('i', $id_actor);
        $stmt -> execute();
        $stmt -> bind_result($id, $nombre, $apellidos, $foto);
        $stmt -> fetch();
        $stmt -> close();
    }
    $con -> close();
    $actor = [
                'id' => $id,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'foto' => $foto
            ];
    if($actor['foto'] == ''){
        $actor['foto'] = 'No disponemos de foto';
    }
    return $actor;
}
##search 
function search($termino_busqueda){##devuelve un array bidimensional; contiene un array de peliculas y un array de actores; en caso de no haber encontrado nada; devuelve -1
    include '../keys/datos.php';
    ##convierto el termino de busqueda en un array de tokens 
    $tokens = explode(" ",trim($termino_busqueda));
    $resultado = [];
    $con = new mysqli ($_HOSTNAME, $_USERNAME, $_USERPASS, $_DATABASE);
    if($con -> connect_errno){
        exit();
    }
    $candidato = [];
    ##peliculas
        $tabla = 'peliculas';
        $id;
        $titulo;
        $director;
        $genero;
        $pais;
        $fecha;
        $cartel;
        $peliculas = [];
        foreach($tokens as $token){##cada token es una palabra de las que el usuario a insertado en el campo de busqueda
            $search_key = '%'.$token.'%';
            $query = 'SELECT DISTINCT * FROM peliculas WHERE titulo LIKE ? OR director LIKE ? OR genero LIKE ? OR pais LIKE ? OR fecha LIKE ?';
            if($stmt = $con -> prepare($query)){
                $stmt -> bind_param('sssss', $search_key, $search_key, $search_key, $search_key, $search_key);
                $stmt -> execute();
                $stmt -> bind_result($id, $titulo, $director, $genero, $pais, $fecha, $cartel);
                while($stmt -> fetch()){
                    $candidato = [
                        'tabla' => $tabla,
                        'id' => $id,
                        'titulo' => $titulo,
                        'director' => $director,
                        'genero' => $genero,
                        'pais' => $pais,
                        'fecha' => $fecha,
                        'cartel' => $cartel
                    ];
                    if(!check_candidato($peliculas, $candidato)){ 
                        array_push($peliculas, $candidato);
                    }
                }
                $stmt -> close();  
            }
        }
    ##tabla actores
        $tabla = 'actores';
        $id;
        $nombre;
        $apellidos;
        $foto;
        $actores = [];
        foreach($tokens as $token){
            $search_key = '%'.$token.'%';
            $query = 'SELECT DISTINCT * FROM actores WHERE nombre LIKE ? OR apellidos LIKE ?';
            if($stmt = $con -> prepare($query)){
                $stmt -> bind_param('ss', $search_key, $search_key);
                $stmt -> execute();
                $stmt -> bind_result($id, $nombre, $apellidos, $foto);
                while($stmt -> fetch()){
                    $candidato = [
                        'tabla' => $tabla,
                        'id' => $id,
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'foto' => $foto
                    ];
                    if(!check_candidato($actores, $candidato)){ 
                        array_push($actores, $candidato);
                    }
                    
                }
                $stmt -> close();
            }
            
        }
    $con -> close();
    if(count($peliculas) > 0){
        array_push($resultado, $peliculas);
    }
    if(count($actores) > 0){
        array_push($resultado, $actores);
    }
    return count($resultado) > 0 ? $resultado : -1;
}##devuelve true si el candidato existe en el resultado; false si no
function check_candidato($resultado, $candidato){
    if(count($resultado) == 0){
        return false;
    }
    foreach($resultado as $value){
        if($value['id']==$candidato['id']){
            return true;
        }
    }
    return false;
}
?>