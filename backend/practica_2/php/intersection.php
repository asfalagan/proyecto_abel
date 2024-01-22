<?php
include './dml.php';
##deletes
if(isset($_POST['verificadoeliminarpelicula'])){
    if(isset($_GET['x'])){
        $id_pelicula = $_GET['x'];
        if(delete_pelicula($id_pelicula)){
            echo '<p class="phpalert">Película eliminada correctamente</p>
                  <br>
                  <a href="./index.php">Volver a la página de Inicio</a>    
            ';
    
        }else{
            echo '<p class="phpalert">No hemos podido eliminar la película</p>
                  <br>
                  <a href="./index.php">Volver a la página de Inicio</a>    
            ';
        }
    }else{
        echo '<p class="phpalert">No hemos podido eliminar la película</p>
              <br>
              <a href="./index.php">Volver a la página de Inicio</a>    
        ';
    } 
}
if(isset($_POST['verificadoeliminaractor'])){
    if(isset($_GET['x'])){
        $id_actor = $_GET['x'];
        if(delete_actor($id_actor)){
            echo '<p class="phpalert">Actor eliminado correctamente</p>
                  <br>
                  <a href="./index.php">Volver a la página de Inicio</a>    
            ';
    
        }else{
            echo '<p class="phpalert">No hemos podido eliminar a este actor</p>
                  <br>
                  <a href="./index.php">Volver a la página de Inicio</a>    
            ';
        }
    }else{
        echo '<p class="phpalert">No hemos podido eliminar a este actor</p>
              <br>
              <a href="./index.php">Volver a la página de Inicio</a>    
        ';
    } 
}
?>