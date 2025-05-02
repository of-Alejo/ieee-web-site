<?php 

/*
    APP
    Hace el llamado para las funciones a utilizar en el sitio web e inicializa la DB
*/

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/vlucas/phpdotenv/src/Dotenv.php';

// Añadir Dotenv (ocultar datos) [.env].
$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load(); 

require_once 'functions.php';
require_once 'database.php';