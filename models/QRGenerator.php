<?php

/**
 * Generador de códigos QR
 * Usa api.qrserver.com (servicio online - sin instalación requerida)
 * Compatible PHP 5.3
 */
class QRGenerator {
    
    // Servicio online para generar QR
    private static $QR_API = 'https://api.qrserver.com/v1/create-qr-code/';
    
    /**
     * Generar hash SHA256 único para el QR
     * 
     * @param string $email Email del asistente
     * @param int $id ID en BD
     * @return string Hash único de 64 caracteres
     */
    public static function generateHash($email, $id) {
        $data = $email . '|' . $id . '|' . date('Y-m-d');
        $hash = hash('sha256', $data);
        return $hash;
    }
    
    /**
     * Generar URL del QR que redirige al sistema de ingreso
     * 
     * @param string $qrHash Hash único del QR
     * @param string $baseURL URL base del sistema (ej: http://localhost:3000)
     * @return string URL completa del QR generado
     */
    public static function generateQRURL($qrHash, $baseURL = '') {
        // URL a la que redirige el QR
        $redirectURL = $baseURL . '/tet/ingreso?code=' . urlencode($qrHash);
        
        // Codificar para el parámetro data
        $data = urlencode($redirectURL);
        
        // Retornar URL completa del QR
        return self::$QR_API . '?size=300x300&data=' . $data;
    }
    
    /**
     * Obtener QR desde la BD usando el hash
     * Leer desde BD y generar la imagen URL
     * 
     * @param string $qrHash Hash del QR desde BD
     * @param string $baseURL URL base del sistema (ej: http://localhost:3000)
     * @return string URL de la imagen QR generada
     */
    public static function getQRFromHash($qrHash, $baseURL = '') {
        // Validar que es un hash válido (64 caracteres hexadecimales)
        if (empty($qrHash) || strlen($qrHash) !== 64 || !ctype_xdigit($qrHash)) {
            return '';
        }
        
        // Generar la URL de redirección
        return self::generateQRURL($qrHash, $baseURL);
    }
    
    /**
     * Obtener QR para mostrar HTML (IMG tag)
     * 
     * @param string $qrHash Hash del QR desde BD
     * @param string $baseURL URL base del sistema
     * @param int $size Tamaño en píxeles (por defecto 300)
     * @return string HTML img tag del QR
     */
    public static function displayQRImage($qrHash, $baseURL = '', $size = 300) {
        $qrURL = self::getQRFromHash($qrHash, $baseURL);
        
        if (empty($qrURL)) {
            return '<p style="color: red;">QR inválido</p>';
        }
        
        return '<img src="' . htmlspecialchars($qrURL) . '" 
                     alt="Código QR" 
                     title="Escanea este QR para ingreso"
                     width="' . $size . '" 
                     height="' . $size . '" 
                     style="border: 2px solid #333; border-radius: 8px;">';
    }
}
