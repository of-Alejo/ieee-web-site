<?php

/*
    APP
    Central initialization: Dotenv, functions, DB connection
*/

// ------------------------------
// AUTOLOAD (Composer)
// ------------------------------
//require_once __DIR__ . '/../vendor/autoload.php';
// ------------------------------
// LOAD ENVIRONMENT VARIABLES (phpdotenv 2.x compatible PHP 5.3)
// ------------------------------
if (file_exists(__DIR__ . '/../.env')) {
    $envFile = file_get_contents(__DIR__ . '/../.env');
    $lines = explode("\n", $envFile);
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Ignorar comentarios y líneas vacías
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Parsear KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Eliminar comillas si las tiene
            $value = trim($value, '"\'');
            
            // Asignar a $_ENV
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}

// ------------------------------
// LOAD UTILITIES
// ------------------------------
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/database.php';
//require_once __DIR__ . '/../tools/createAdmins.php';
