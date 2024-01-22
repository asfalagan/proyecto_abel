<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insercción de Datos</title>
</head>
<body>
<?php
        include '../keys/datos.php';
        include '../php/dml.php';
        echo '<br><a href="./index.php">Volver a Inicio</a>';
        if(isset($_POST['pelicula'])){
            echo '
            <p>Inserta los datos de la película:</p>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="titulo" required> Título<br>
                <input type="text" name="director" required> Director<br>
                <input type="date" name="fecha" required> Lanzamiento<br>
                <input type="text" name="genero" required> Género<br>
                <input type="text" name="pais" required> País<br>
                <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
                Cargar cartel de la película: <br><input type="file" name="cartel" accept=".png, .jpg"><br>
                Límite de archivo 50KB<br>
                <input type="submit" name="agregarpelicula" value="Agregar Pelicula"></input>
            </form>';
                
                
        }else if(isset($_POST['actor'])){
            echo '
            <p>Inserta los datos del actor:</p>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="nombre" required> Nombre<br>
                <input type="text" name="apellidos" required> Apellidos<br>
                <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
                Cargar foto del actor: <br><input type="file" name="foto" accept=".png, .jpg"><br>
                Límite de archivo 50KB<br>
                <input type="submit" name="agregaractor" value="Agregar Actor"></input>
            </form>';
        }##else if(isset($_POST['actuacion'])){
                //dibujo formulario correspondiente
        #}
        ## 'un director no puede tener dos peliculas con el mismo nombre -> utilizamos esto para obtener id de la pelicula';
        if(isset($_POST['agregarpelicula'])){
            if(insert_pelicula($_POST, $_FILES)){
                echo '<p>Hemos añadido la película correctamente<p>';
            }else{
                echo '<p>Esa película ya existe en nuestro catálogo o ha sucedido un error<p>';
            }
        }
        if(isset($_POST['agregaractor'])){
            if(insert_actor($_POST, $_FILES)){
                echo '<p>Hemos añadido al actor correctamente<p>';
            }else{
                echo '<p>Ese actor ya existe en nuestro catálogo o ha sucedido un error<p>';
            }
        }
    ?>
</body>
</html>