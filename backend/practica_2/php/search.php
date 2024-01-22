<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador</title>
</head>
<body>
    <?php
        echo '<br><a href="./index.php">Volver a Inicio</a><br>';
    ?>
    <br>
    <form action="" method="post">
        <input type="text" name="busqueda" required/>
        <button type="submit" name="buscar">Buscar</button>
    </form>
    <?php
        include './dml.php';
        echo '<br>';
        if(isset($_POST['buscar'])){
            $resultado = search($_POST['busqueda']);
            if(is_iterable($resultado)){
                $cantidad = count($resultado);
            }else{
                $cantidad = 0;
            }
            
            if($cantidad < 1){
                echo '<p>No hemos encontrado nada a partir de ese término de búsqueda. Intenta otra búsqueda</p>';
            }
            ##ahora el resultado puede contener peliculas, actores o ambas: 
            if($cantidad == 1){##pueden ser actores o peliculas
                if($resultado[0][0]['tabla'] == 'peliculas'){##son peliculas
                    echo '<p>Hemos encontrado estas películas acordes a tu búsqueda</p>';
                    echo '
                            <table>
                                <tr>
                                    <th>Título</th><th>Director</th><th>Género</th><th>Lanzamiento</th><th>País</th><th>Cartel</th><th>Opciones</th>
                                </tr>
                        ';
                            foreach($resultado[0] as $pelicula){
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
                }else if($resultado[0][0]['tabla'] == 'actores'){##son actores
                    echo '<p>Hemos encontrado estos actores acordes a tu búsqueda</p>';
                    echo '
                            <table>
                                <tr>
                                    <th>Nombre</th><th>Apellidos</th><th>Foto</th><th>Controles</th>
                                </tr>
                            ';
                            foreach($resultado[0] as $actor){
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
                }
            }else if($cantidad == 2){##son peliculas y actores
                echo '<p>Hemos encontrado estas películas acordes a tu búsqueda</p>';
                echo '
                            <table>
                                <tr>
                                    <th>Título</th><th>Director</th><th>Género</th><th>Lanzamiento</th><th>País</th><th>Cartel</th><th>Opciones</th>
                                </tr>
                        ';
                            foreach($resultado[0] as $pelicula){
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
                    echo '<p>Hemos encontrado estos actores acordes a tu búsqueda</p>';
                
                    
                    echo '
                            <table>
                                <tr>
                                    <th>Nombre</th><th>Apellidos</th><th>Foto</th><th>Controles</th>
                                </tr>
                            ';
                            foreach($resultado[1] as $actor){
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
                        
            }
        }
    ?>
</body>
</html>