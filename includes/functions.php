<?php

/*
    FUNCIONES
    Aquí existen funciones que se pueden usar en cualquier lugar.
*/

/**
 * Start session if not already started
 *
 * @return bool True if session started or already active
 */
function startSession() {
    if (function_exists('session_status')) {
        if (session_status() == PHP_SESSION_NONE) {
            return session_start();
        }
    } else {
        if (session_id() == '') {
            return session_start();
        }
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
    // Solo funciona en desarrollo
    if (!isProduction()) {
        echo "<pre>";
        var_dump($variable);
        echo "</pre>";
        exit;
    } else {
        // En producción, solo log básico sin exponer al público
        error_log("Debug llamado en producción: " . print_r($variable, true));
    }
}

/**
 * Sanitiza código HTML y lo retorna.
 *
 * @param mixed $html Código HTML a sanitizar
 * @return mixed Retorna el código HTML sanitizado 
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    
    // Sanitización para PHP 5.3
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
    // Limitar longitud para prevenir ataques DoS
    if (strlen($input) > 1000) {
        $input = substr($input, 0, 1000);
    }
    
    return $input;
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