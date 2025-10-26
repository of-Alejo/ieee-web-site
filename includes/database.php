<?php

/*
    DATABASE CONNECTION
    Uses PDO with environment variables for database credentials.
    Connects to production or development database depending on environment.
*/

require_once __DIR__ . '/../models/ActiveRecord.php';

try {
    // Determine connection parameters based on environment
    if (isProduction()) {
        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPass = $_ENV['DB_PASS'];
    } else {
        $dbHost = $_ENV['DB_HOST_DEV'];
        $dbName = $_ENV['DB_NAME_DEV'];
        $dbUser = $_ENV['DB_USER_DEV'];
        $dbPass = $_ENV['DB_PASS_DEV'];
    }
    // Validate required environment variables
    if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
        throw new Exception('Missing required database environment variables');
    }

    // Create PDO connection
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8";
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    // Set PDO attributes for better error handling and security
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('SET NAMES utf8');

    // Inject database connection into ActiveRecord
    ActiveRecord::setDB($pdo);

} catch (PDOException $error) {
    // Log directory creation
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Log error without exposing sensitive data
    $logMessage = date('Y-m-d H:i:s') . " - DB Connection Error: " . $error->getMessage() . PHP_EOL;
    error_log($logMessage, 3, $logDir . '/error_db.log');

    // In development, show detailed error
    if (!isProduction()) {
        die("Database Error (Development): " . htmlspecialchars($error->getMessage()));
    }

    // In production, redirect to error page
    header("Location: /500");
    exit();

} catch (Exception $error) {
    // Catch other exceptions (e.g., missing env vars)
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logMessage = date('Y-m-d H:i:s') . " - Exception: " . $error->getMessage() . PHP_EOL;
    error_log($logMessage, 3, $logDir . '/error_db.log');

    if (!isProduction()) {
        die("Configuration Error: " . htmlspecialchars($error->getMessage()));
    }

    header("Location: /500");
    exit();
}
