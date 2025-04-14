<?php

use Model\Cita;
use Model\User;

/*
    FUNCIONES
    Aquí existen funciones que se pueden usar en cualquier lugar.
*/

/**
 * Inicia sesión una vez
 *
 * @return bool Verdadero si la sesión esta iniciada con éxito.
 */
function startSession() : bool {
    if (session_status() == PHP_SESSION_NONE) 
        return session_start();
    return true;
}


/**
 * Cambiar esta variable a true si ya esta en producción
 *
 * @return bool Retorna True si esta en Modo Producción (Cambiar en functions.php)
 */
function isProduction() : bool{
    return $_ENV['ENVIRONMENT'] === 'production';
}


// Imprime la información de la variable.
function debug(mixed $variable) : void {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

/**
 * Sanitiza código HTML y lo retorna.
 *
 * @param mixed $html Código HTML a sanitizar
 * @return mixed Retorna el código HTML sanitizado 
 */
function sanitize(mixed $html) : mixed {
    return !is_null($html) ? htmlspecialchars($html) : "";
}

/**
 * Verifica si la sesión está activa (La sesión tiene datos).
 *
 * @return bool Retorna True si existe alguna Sesión
 */
function isAuth() : bool {
    startSession();
    return isset($_SESSION['name']) && !empty($_SESSION);
}

/**
 * Función para obtener el subdominio del dominio actual.
 *
 * @return string Retorna string con el subdominio.
 */
function getSubDomain() : string{
    $url_actual = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';

    // Para variables del método GET
    $tieneVarGet = strstr($url_actual, '?', true);
    if( $tieneVarGet )
        $url_actual = $tieneVarGet;
    
    return $url_actual;
}