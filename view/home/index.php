<?php require_once "navbar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home csma</title>
    <link rel="stylesheet" href="../style/style.css">
    <?php require_once "../../model/libraries/lib.php"; ?>
</head>
<body>

    <div class="container-x">
        <div class="jumbotron mt-5 ml-5 w-50">
        <h1 class="display-4 ">Bienvenido a la gestion y control de <strong>Bit Mathews</strong>
        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-upc-scan" viewBox="0 0 16 16">
        <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
        <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
        <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
        </svg>
        </h1>
        <p class="lead">desde este software puedes vender, recargar y gestionar la cartera de la moneda digital.</p>
        <hr class="my-4">
        <p>Si necesitas ayuda, da click en el siguiente boton, recuerda dependiendo de tu rol tendras diferentes opciones.</p>
        <p class="lead">
            <a class="btn btn-warning btn-lg" href="#" role="button">Learn more</a>
        </p>
        </div>
    </div>
    
</body>
</html>

<style>
    body{
        /* background-image: radial-gradient(circle at 84.17% 84.17%, #aff5ff 0, #83daec 25%, #42b9d4 50%, #0099be 75%, #007fae 100%); */
        /* background-repeat: no-repeat;
        height: 100vh; */
        background-image: url(../media/recursos/fondo.png);
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
    }
</style>