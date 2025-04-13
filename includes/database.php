<?php

/*
    DATABASE
    Usamos PDO para el manejo de base datos en este sitio web, ver documentación.
    Aquí usamos variables de entorno para la información de la base datos.
*/

use Model\ActiveRecord;

try {
    // Conexión
    if(isProduction()){
        // Para producción
        $pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", 
            $_ENV['DB_USER'], 
            $_ENV['DB_PASS']
        );
    }else{
        // Para desarrollo
        $pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST_DEV']};dbname={$_ENV['DB_NAME_DEV']}", 
            $_ENV['DB_USER_DEV'], 
            $_ENV['DB_PASS_DEV']
        );
    }
    
    // Habilitar el modo de manejo de errores de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Permitir el manejo de errores
    $pdo->exec('SET NAMES utf8mb4'); // Establecer el conjunto de caracteres
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Desactivar el modo de emulación

    ActiveRecord::setDB($pdo);
    
} catch (PDOException $error) {
    // Manejar errores específicos de PDO
    error_log("Error de conexión: " . $error->getMessage(), 3, __DIR__ . 'error_db.log');
    die("Error de PDO: " . $error->getMessage());
} catch (Exception $error) {
    // Manejar otras excepciones
    error_log("Error de conexión: " . $error->getMessage(), 3, __DIR__ . 'error_db.log');
    die("Error: " . $error->getMessage());
}