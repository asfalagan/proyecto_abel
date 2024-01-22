<?php
echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio</title>
    </head>
    <body>
        <div class="contenedorindex">
            <form action="get_peliculas.php" method="post">
                <button type="submit" name="peliculas">Catálogo de películas</button>
            </form>
            <form action="get_actores.php" method="post">
                <button type="submit" name="actores">Catálogo de actores</button>
            </form>
            <form action="search.php" method="post">
                <button type="submit" name="search">Buscador</button>
            </form>
        </div>
    </body>
    </html>
';
?>

