<?php 

/*
    INDEX
    En este archivo empieza todo, aquí se asigna que URLs están permitidas para el sitio web,
    para esto se usa el ROUTER para tan caso, donde se envía la URL, y se le envía la ubicación de la función
    asociada para dicha URL, además llamada a '/includes/app.php', que contiene información sobre la base de datos,
    y funciones esenciales. Con ->get(string URL, array función), ->post(string URL, array función) se asigna lo antes dicho.
*/

//require_once __DIR__ . '/../includes/app.php';

require_once __DIR__ . '/../controllers/Main.php';
require_once __DIR__ . '/../controllers/Error.php';
require_once __DIR__ . '/../Router.php';

// Instancia del router
$router = new Router();

// Validadores de URLs
$router->get('/', array('Main', 'index'));
$router->get('/eventos', array('Main', 'events'));
$router->get('/tet', array('Main', 'tet_2025'));
$router->get('/anuario', array('Main', 'year_book'));
$router->get('/galeria', array('Main', 'gallery'));
$router->get('/organizacion', array('Main', 'organization'));
$router->get('/404', array('AppError', 'e404'));
$router->get('/500', array('AppError', 'e500'));

$router->get('/admin', array('Main', 'admin'));

// Verifica las rutas
$router->checkRoutes();