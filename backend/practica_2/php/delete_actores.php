<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Actores</title>
</head>
<body>
    <?php
        include './dml.php';
        echo '<br><a href="./index.php">Volver a Inicio</a>';
        $confirmacion = '
        <form method="post" action="" class="phpalert">
            <input type="submit" name="eliminar" value="Eliminar Actor"></input>
        </form>
        ';
        ##Cuando pulsen a eliminar actor quiero que el mensaje de confirmacion cambie (re-confirmacion)
        if(isset($_POST['eliminar'])){
            $id_actor = $_GET['x'];
            $confirmacion = '
            <form method="post" action="intersection.php?x='.$id_actor.'" class="phpalert">
                <label for="verificadoeliminaractor">Eliminar un actor no se puede deshacer, confirma que quieres eliminar a este actor</label>
                <br>
                <input type="submit" name="verificadoeliminaractor" value="Confirmar"></input>
            </form>
            ';
        }
        ##recupero el id del actor con get para mostrar los datos del actor
        if(isset($_GET['x'])){
            $id_actor = $_GET['x'];
            ##muestro los datos actuales de la pelicula
            $actor = get_actor($id_actor);
            ##echo '<p class="phpalert">Estás intentando eliminar esta película</p>';
            echo '
            <table>
                <tr>
                    <th>Nombre</th><th>Apellidos</th><th>Foto</th>
                </tr>
            ';
            echo '
                <tr>
                    <td>'.$actor['nombre'].'</td>
                    <td>'.$actor['apellidos'].'</td>
                    <td> <img src="'.$actor['foto'].'" alt="No disponemos de foto"></td>
                </tr>
            ';
            echo'
                </table>
            ';
            ##confirmo eliminar
            echo $confirmacion;
        }
        
?>
</body>
</html>