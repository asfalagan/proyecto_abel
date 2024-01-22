<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Actores</title>
</head>
<body>
    <?php
        include './dml.php';
        echo '<br><a href="./index.php">Volver a Inicio</a><br>';
        ##recupero el id de la actor con get para mostrar los datos de la actor 
        if(isset($_GET['x'])){
            $id_actor = $_GET['x'];
            ##muestro los datos actuales de la actor
            $actor = get_actor($id_actor);
            
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
            ##solicito los nuevos datos
            echo '<p>Rellena solo los campos que quieras modificar</p>';
            echo '
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="text" name="nombre"> Nombre<br>
                    <input type="text" name="apellidos"> Apellidos<br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
                    Cargar foto del actor: <br><input type="file" name="foto" accept=".png, .jpg"><br>
                    Límite de archivo 50KB<br>
                    <input type="submit" name="modificaractor" value="Modificar Actor"></input>
                </form>';
        }else{
            echo '<p>Vaya, ha ocurrido un error y no se qué actor quieres modificar :( </p>';
 
        }
        if(isset($_POST['modificaractor'])){
            if(update_actor($_POST, $_FILES, $actor)){
                echo '<p>Hemos modificado el actor correctamente<p>';

            }else{
                echo '<p>No hemos podido modificar el actor, inténtalo más tarde<p>';

            }
        }
    ?>
</body>
</html>