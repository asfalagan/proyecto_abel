
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Películas</title>
</head>
<body>
    <?php
        include './dml.php';
        echo '<br><a href="./index.php">Volver a Inicio</a>';
        $confirmacion = '
        <form method="post" action="" class="phpalert">
            <input type="submit" name="eliminar" value="Eliminar Película"></input>
        </form>
        ';
        ##Cuando pulsen a eliminar pelicula quiero que el mensaje de confirmacion cambie (re-confirmacion)
        if(isset($_POST['eliminar'])){
            $id_pelicula = $_GET['x'];
            $confirmacion = '
            <form method="post" action="intersection.php?x='.$id_pelicula.'" class="phpalert">
                <label for="verificadoeliminarpelicula">Eliminar una película no se puede deshacer, confirma que quieres eliminar la película</label>
                <br>
                <input type="submit" name="verificadoeliminarpelicula" value="Confirmar"></input>
            </form>
            ';
        }
        ##recupero el id de la pelicula con get para mostrar los datos de la pelicula 
        if(isset($_GET['x'])){
            $id_pelicula = $_GET['x'];
            ##muestro los datos actuales de la pelicula
            $pelicula = get_pelicula($id_pelicula);
            ##echo '<p class="phpalert">Estás intentando eliminar esta película</p>';
            echo '
            <table>
                <tr>
                    <th>Título</th><th>Director</th><th>Género</th><th>País</th><th>Lanzamiento</th><th>Cartel</th>
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
            ##confirmo eliminar
            echo $confirmacion;
        }
        
?>
</body>
</html>