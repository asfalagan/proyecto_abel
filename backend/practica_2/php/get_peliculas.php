<?php
    include './dml.php';
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catálogo de películas</title>
    </head>
    <body>
    ';
    echo '<br><a href="./index.php">Volver a Inicio</a>';
    ##compruebo si tengo peliculas en el catalogo
    $peliculas = get_peliculas();
    if($peliculas == -1){
        echo '
            <p>Actualmente no hay ninguna película que mostrar</p>
        ';
        echo '<p>Si quieres añadir películas o actores, pulsa el siguiente enlace: <p>';
        echo '<a href="./insert.php">Agregar</a>';

    }else{
        echo '<p>Si quieres añadir películas o actores, pulsa el siguiente enlace: <p>';
        echo '<a href="./insert.php">Agregar</a><br>';

        ##if(is_iterable($peliculas)){ //con la comprobacion del -1 ya compruebo si es o no iterable
            echo '
            <table>
                <tr>
                    <th>Título</th><th>Director</th><th>Género</th><th>Lanzamiento</th><th>País</th><th>Cartel</th><th>Opciones</th>
                </tr>
            ';
            foreach($peliculas as $pelicula){
                echo '
                    <tr>
                        <td>'.$pelicula['titulo'].'</td>
                        <td>'.$pelicula['director'].'</td>
                        <td>'.$pelicula['genero'].'</td>
                        <td>'.$pelicula['fecha'].'</td>
                        <td>'.$pelicula['pais'].'</td>
                        <td> <img src="'.$pelicula['cartel'].'" alt="No disponemos de cartel"></td>
                        <td>
                            <a href="./update_peliculas.php?x='.$pelicula['id'].'">Modificar Película</a>
                            <a href="./delete_peliculas.php?x='.$pelicula['id'].'">Eliminar Película</a>
                        </td>
                    </tr>
                ';
            }
            echo'
                </table>
            ';
        ##}
    }
    echo '
    </body>
    </html>
    ';


?>