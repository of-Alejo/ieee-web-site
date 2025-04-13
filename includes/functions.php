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
    return false;
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
 * Verifica si el límite de registros ha sido alcanzado.
 *
 * @return bool Verdadero si no se ha alcanzado el límite, falso de lo contrario.
 */
/* function isLimitRegisters() : bool{
    startSession();

    // Establecer el límite de velocidad: 3 registros cada 5 minutos
    // TODO cambiar tiempo
    $limitRegisters = 3;
    $limitTime = 60; // en segundos

    // Obtener la dirección IP del cliente
    $ip = $_SERVER['REMOTE_ADDR'];

    if (!isset($_SESSION['rate_limit'][$ip])) {
        // Si no hay sesión activa, crear una nueva y establecer el contador a 1
        $_SESSION['rate_limit'][$ip] = 1;
        $_SESSION['rate_limit_time'][$ip] = time();
    } else {
        // Si hay una sesión activa, verificar si ha excedido el límite de velocidad
        $currentTime = time();
        $previousTime = $_SESSION['rate_limit_time'][$ip];
        $difTime = $currentTime - $previousTime;
        
        if ($difTime < $limitTime) {
            // Si no ha pasado el tiempo límite, incrementar el contador
            $_SESSION['rate_limit'][$ip]++;
            
            // Verificar si se ha excedido el límite de solicitudes
            if ($_SESSION['rate_limit'][$ip] > $limitRegisters) {
                // Aún falta tiempo
                return false;
            }
        } else {
            // Si ha pasado el tiempo límite, reiniciar el contador y el tiempo
            $_SESSION['rate_limit'][$ip] = 1;
            $_SESSION['rate_limit_time'][$ip] = $currentTime;
        }
    }

    // Procesar la solicitud normalmente, ya que no ha excedido el límite de velocidad
    return true;
} */

/**
 * Verifica si el límite de citas ha sido alcanzado.
 *
 * @return bool Verdadero si no se ha alcanzado el límite, falso de lo contrario.
 */
function isLimitCitas() : bool{
    startSession();
    $limit = 4;
    if( !isset($_SESSION['limitCitas']) ) 
        $_SESSION['limitCitas']=0;
    elseif( $_SESSION['limitCitas'] >= $limit ) 
        return false;
    else 
        $_SESSION['limitCitas'] = intval( $_SESSION['limitCitas']) + 1;
    return true;
}

/**
 * Actualiza la sesión del usuario con información de citas y resetea el contador diario.
 */
function updateSession() : void{
    startSession();
    $_SESSION['cita'] = 0;
    if( !isset($_SESSION['id']) ) 
        $_SESSION['name'] = "Acceder";
        return;
    
    // Limite citas, cada día se renueva las peticiones de cita
    if (!isset($_SESSION['date']) || $_SESSION['date'] != date("Y-m-d")) {
        $_SESSION['date'] = date("Y-m-d");
        $_SESSION['limitCitas'] = 0;
    }
    
    // Info de cita
    $cita = Cita::where('userId', $_SESSION['id']);
    $user = User::find($_SESSION['id']);
    $_SESSION['cita'] = $user->cita;
    
    if( !is_object($cita) ){
        unset(  $_SESSION['dateCita'],
                $_SESSION['hourCita'],
                $_SESSION['acceptCita'],
                $_SESSION['acceptCita'],
                $_SESSION['calendarId']
        );

        return;
    }else{
        $_SESSION['dateCita'] = $cita->date;
        $_SESSION['hourCita'] = $cita->hour;
        $_SESSION['acceptCita'] = $cita->accept;
    }
    
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