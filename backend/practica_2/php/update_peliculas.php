<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Peliculas</title>
</head>
<body>
    <?php
        include './dml.php';
        echo '<br><a href="./index.php">Volver a Inicio</a><br>';
        ##recupero el id de la pelicula con get para mostrar los datos de la pelicula 
        if(isset($_GET['x'])){
            $id_pelicula = $_GET['x'];
            ##muestro los datos actuales de la pelicula
            $pelicula = get_pelicula($id_pelicula);
            echo '
            <table>
                <tr>
                    <th>Título</th><th>Director</th><th>Género</th><th>Lanzamiento</th><th>Pais</th><th>Cartel</th>
                </tr>
            ';
            echo '
                <tr>
                    <td>'.$pelicula['titulo'].'</td>
                    <td>'.$pelicula['director'].'</td>
                    <td>'.$pelicula['genero'].'</td>
                    <td>'.$pelicula['fecha'].'</td>
                    <td>'.$pelicula['pais'].'</td>
                    <td> <img src="'.$pelicula['cartel'].'" alt="No disponemos de cartel"></td>
                </tr>
            ';
            echo'
                </table>
            ';
            ##solicito los nuevos datos
            echo '<p>Rellena solo los campos que quieras modificar</p>';
            echo '
                  <form action="" method="post" enctype="multipart/form-data">
                    <input type="text" name="titulo"> Título<br>
                    <input type="text" name="director"> Director<br>
                    <input type="date" name="fecha"> Lanzamiento<br>
                    <input type="text" name="genero"> Género<br>
                    <input type="text" name="pais"> País<br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
                    Cargar cartel de la película: <br><input type="file" name="cartel" accept=".png, .jpg"><br>
                    Límite de archivo 50KB<br>
                    <input type="submit" name="modificarpelicula" value="Modificar Película"></input>
                  </form>';
        }else{
            echo '<p>Vaya, ha ocurrido un error y no se qué película quieres modificar :( </p>';
        }
        if(isset($_POST['modificarpelicula'])){
            if(update_pelicula($_POST, $_FILES, $pelicula)){
                echo '<p>Hemos modificado la película correctamente<p>';
            }else{
                echo '<p>No hemos podido modificar la película, intentalo más tarde<p>';
            }
        }
    ?>
</body>
</html>