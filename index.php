<?php
// SETTING SLIM APP & DB
require 'vendor/autoload.php';
require 'src/config/db.php';
$config = ['settings' => [
    'addContentLengthHeader' => false,
]];
$app = new \Slim\App($config);
$app->get('/', function ($request, $response) {
    $reedme=file_get_contents('REEDME.txt');
    print_r('
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>API - EDUCACION V1</title>
        </head>
        <body>
            <h3>API EDUCACION V.1.0</h3>
            <h4>Examen de conocimientos PHP</h4>
            <p>API desarrollada bajo la estructura del framework SLIM 3.12 de PHP</p><br>'.
            nl2br($reedme).
        '</body>
        </html>
    ');
});

//ROUTES
require 'src/rutas/curso.php';
require 'src/rutas/leccion.php';
require 'src/rutas/pregunta.php';

// RUN APP
$app->run();
?>

