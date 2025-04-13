<?php 

/*
    INDEX
    En este archivo empieza todo, aquí se asigna que URLs están permitidas para el sitio web,
    para esto se usa el ROUTER para tan caso, donde se envía la URL, y se le envía la ubicación de la función
    asociada para dicha URL, además llamada a '/includes/app.php', que contiene información sobre la base de datos,
    y funciones esenciales. Con ->get(string URL, array función), ->post(string URL, array función) se asigna lo antes dicho.
*/

require_once __DIR__ . '/../includes/app.php';

use APIs\AdminApi;
use APIs\CitasApi;
use APIs\UserApi;
use Controllers\Admin;
use Controllers\SecondaryPages;
use Controllers\PrincipalPages;
use Controllers\Politic;
use MVC\Router;

$router = new Router();

// Validadores de URLs
$router->get('/', [PrincipalPages::class, 'index']);
$router->get('/buscador', [PrincipalPages::class, 'search']);
$router->get('/blog', [PrincipalPages::class, 'blog']);
$router->get('/noticias', [PrincipalPages::class, 'news']);
$router->get('/location', [PrincipalPages::class, 'location']);

// Páginas secundarias
$router->get('/confirmarRegistro', [SecondaryPages::class, 'confirmRegister']);
$router->get('/recuperarUsuario', [SecondaryPages::class, 'restoreUser']);
$router->post('/recuperarUsuario', [SecondaryPages::class, 'restoreUser']);

// Términos y condiciones
$router->get('/politica-privacidad', [Politic::class, 'privacy']);
$router->get('/terminos-condiciones', [Politic::class, 'termsConditions']);

// Admin
$router->get('/admin', [Admin::class, 'index']);

/* API */
// Registro
$router->post('/api/register', [UserApi::class, 'register']);
$router->post('/api/login', [UserApi::class, 'login']);
$router->post('/api/logout', [UserApi::class, 'logout']);
$router->post('/api/reSendConfirmEmail', [UserApi::class, 'reSendConfirmEmail']);
$router->post('/api/sendRecoverEmail', [UserApi::class, 'sendConfirmEmail']);

// Citas
$router->post('/api/scheduleCita', [CitasAPI::class, 'scheduleCita']);
$router->post('/api/removeCita', [CitasAPI::class, 'removeCita']);

// Admin
$router->post('/api/admin/acceptCita', [AdminAPI::class, 'acceptCita']);
$router->post('/api/admin/cancelCita', [AdminAPI::class, 'cancelCita']);


// Verifica si la ruta está bien, y tiene un función asociada para renderizar.
$router->checkRoutes();