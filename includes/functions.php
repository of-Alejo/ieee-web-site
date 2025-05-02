<?php

/*
    FUNCIONES
    Aquí existen funciones que se pueden usar en cualquier lugar.
*/

/**
 * Inicia sesión una vez
 *
 * @return bool Verdadero si la sesión está iniciada con éxito.
 */
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        return session_start();
    }
    return true;
}

/**
 * Cambiar esta variable a true si ya está en producción
 *
 * @return bool Retorna True si está en Modo Producción (Cambiar en functions.php)
 */
function isProduction() {
    return isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] === 'production';
}

// Imprime la información de la variable.
function debug($variable) {
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
function sanitize($html) {
    return !is_null($html) ? htmlspecialchars($html) : "";
}

/**
 * Verifica si la sesión está activa (La sesión tiene datos).
 *
 * @return bool Retorna True si existe alguna Sesión
 */
function isAuth() {
    startSession();
    return isset($_SESSION['name']) && !empty($_SESSION);
}

/**
 * Función para obtener el subdominio del dominio actual.
 *
 * @return string Retorna string con el subdominio.
 */
function getSubDomain() {
    $url_actual = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');

    // Para variables del método GET
    $tieneVarGet = strstr($url_actual, '?', true);
    if ($tieneVarGet) {
        $url_actual = $tieneVarGet;
    }
    
    return $url_actual;
}