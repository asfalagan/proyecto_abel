<?php
    include './dml.php';
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catálogo de Actores</title>
    </head>
    <body>
    ';
    echo '<br><a href="./index.php">Volver a Inicio</a>';
    ##compruebo si tengo actores en el catalogo
    $actores = get_actores();
    if($actores == -1){
        echo '
            <p>Actualmente no hay ningún actor que mostrar</p>
        ';
        echo '<p>Si quieres añadir películas o actores, pulsa el siguiente enlace: <p>';
        echo '<a href="./insert.php">Agregar</a>';
        
    }else{
        echo '<p>Si quieres añadir películas o actores, pulsa el siguiente enlace: <p>';
        echo '<a href="./insert.php">Agregar</a><br>';
        
        
        ##if (is_iterable($actores)){ con el -1 ya compruebo si es iterable
            echo '
            <table>
                <tr>
                    <th>Nombre</th><th>Apellidos</th><th>Foto</th><th>Controles</th>
                </tr>
            ';
            foreach($actores as $actor){
                echo '
                    <tr>
                        <td>'.$actor['nombre'].'</td>
                        <td>'.$actor['apellidos'].'</td>
                        <td> <img src="'.$actor['foto'].'" alt="No disponemos de foto"></td>
                        <td>
                            <a href="./update_actores.php?x='.$actor['id'].'">Modificar Actor</a>
                            <a href="./delete_actores.php?x='.$actor['id'].'">Eliminar Actor</a>
                        </td>
                    </tr>
                ';
            }
            echo'
                </table>
            ';
        #}
    }
    echo '
    </body>
    </html>
    ';


?>