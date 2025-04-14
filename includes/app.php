<?php 

/*
    APP
    Hace el llamado para las funciones a utilizar en el sitio web e inicializa la DB
*/

// Usa variables de entorno .ENV
use Dotenv\Dotenv;
require __DIR__ . '/../vendor/autoload.php';

// Añadir Dotenv (hide datos) [.env].
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

require 'functions.php';
require 'database.php';