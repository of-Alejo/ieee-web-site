<?php

/*
    DATABASE
    Usamos PDO para el manejo de base datos en este sitio web, ver documentación.
    Aquí usamos variables de entorno para la información de la base datos.
*/

require_once __DIR__ . '/../models/ActiveRecord.php';

try {
    // Conexión
    if (isProduction()) {
        // Para producción
        $pdo = new PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
    } else {
        // Para desarrollo
        $pdo = new PDO(
            "mysql:host=" . $_ENV['DB_HOST_DEV'] . ";dbname=" . $_ENV['DB_NAME_DEV'] . ";charset=utf8",
            $_ENV['DB_USER_DEV'],
            $_ENV['DB_PASS_DEV']
        );

        // Habilitar el modo de manejo de errores de PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    $pdo->exec('SET NAMES utf8'); // Establecer el conjunto de caracteres
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Desactivar el modo de emulación

    // Configurar la conexión en ActiveRecord
    ActiveRecord::setDB($pdo);

} catch (PDOException $error) {
    // Manejar errores específicos de PDO
    // Crear directorio de logs si no existe
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    // Log del error (sin datos sensibles)
    $logMessage = date('Y-m-d H:i:s') . " - Error DB: " . $error->getMessage() . PHP_EOL;
    error_log($logMessage, 3, $logDir . '/error_db.log');
    
    // En desarrollo mostrar detalles
    if (!isProduction()) {
        echo "Error de desarrollo: " . $error->getMessage();
    }
    
    header("Location: /500");
    exit();
} catch (Exception $error) {
    // Manejar otras excepciones
    header("Location: /500");
    exit();
    // error_log("Error de conexión: " . $error->getMessage(), 3, __DIR__ . '/../logs/error_db.log');
    // die("Error: " . $error->getMessage());
}