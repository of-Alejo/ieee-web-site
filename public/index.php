<?php 

/*
    INDEX
    En este archivo empieza todo, aquí se asigna que URLs están permitidas para el sitio web,
    para esto se usa el ROUTER para tan caso, donde se envía la URL, y se le envía la ubicación de la función
    asociada para dicha URL, además llamada a '/includes/app.php', que contiene información sobre la base de datos,
    y funciones esenciales. Con ->get(string URL, array función), ->post(string URL, array función) se asigna lo antes dicho.
*/

require_once __DIR__ . '/../includes/app.php';

use Controllers\Main;
use Controllers\Error;
use MVC\Router;

$router = new Router();

// Validadores de URLs
$router->get('/', [Main::class, 'index']);
$router->get('/eventos', [Main::class, 'events']);
$router->get('/tet', [Main::class, 'tet_2025']);
$router->get('/anuario', [Main::class, 'year_book']);
$router->get('/galeria', [Main::class, 'galery']);
$router->get('/organizacion', [Main::class, 'organization']);
$router->get('/404', [Error::class, 'e404']);
$router->get('/500', [Error::class, 'e500']);

// Verifica si la ruta está bien, y tiene un función asociada para renderizar.
$router->checkRoutes();