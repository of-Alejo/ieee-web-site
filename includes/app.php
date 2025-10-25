<?php

/*
    APP
    Central initialization: Dotenv, functions, DB connection
*/

// ------------------------------
// AUTOLOAD (Composer)
// ------------------------------
require_once __DIR__ . '/../vendor/autoload.php';
// ------------------------------
// LOAD ENVIRONMENT VARIABLES (phpdotenv 2.x compatible PHP 5.3)
// ------------------------------
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

// ------------------------------
// LOAD UTILITIES
// ------------------------------
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/database.php';
//require_once __DIR__ . '/../tools/createAdmins.php';
