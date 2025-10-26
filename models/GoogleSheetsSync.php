<?php 

require_once 'ActiveRecord.php';
require_once 'TetAttendee.php';
require_once 'QRGenerator.php';

class GoogleSheetsSync {
    
    private static $log = array();
    private static $PHOTOS_DIR = null;
    
    /**
     * Obtiene la ruta de carpeta de fotos
     * En Linux, usa rutas absolutas
     */
    private static function getPhotosDir() {
        if (self::$PHOTOS_DIR !== null) {
            return self::$PHOTOS_DIR;
        }
        
        $possiblePaths = array();
        
        // Método 1: DOCUMENT_ROOT (Linux)
        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $possiblePaths[] = $_SERVER['DOCUMENT_ROOT'] . '/build/img/TET/photos/';
        }
        
        // Método 2: Ruta fija conocida para Linux
        $possiblePaths[] = '/www/ieee/docs/web/build/img/TET/photos/';
        
        // Método 3: Relativa desde script
        $currentDir = dirname(__FILE__);
        $projectRoot = dirname($currentDir);
        $possiblePaths[] = $projectRoot . '/web/build/img/TET/photos/';
        
        // Método 4: Alternativas de carpetas temporales
        $possiblePaths[] = '/tmp/tet_photos/';
        $possiblePaths[] = '/var/tmp/tet_photos/';
        
        self::$log[] = 'DEBUG: Buscando directorio de fotos...';
        
        foreach ($possiblePaths as $path) {
            self::$log[] = 'DEBUG: Probando ruta: ' . $path;
            
            if (is_dir($path) && is_writable($path)) {
                self::$log[] = 'DEBUG: ✓ Directorio encontrado y escribible: ' . $path;
                self::$PHOTOS_DIR = $path;
                return self::$PHOTOS_DIR;
            } elseif (is_dir($path) && !is_writable($path)) {
                self::$log[] = 'DEBUG: ✗ Directorio existe pero NO es escribible: ' . $path;
                self::$log[] = 'DEBUG: Intentando cambiar permisos...';
                if (@chmod($path, 0777)) {
                    self::$log[] = 'DEBUG: ✓ Permisos cambiados a 777';
                    if (is_writable($path)) {
                        self::$PHOTOS_DIR = $path;
                        return self::$PHOTOS_DIR;
                    }
                }
            } else {
                self::$log[] = 'DEBUG: Carpeta no existe, intentando crear...';
                if (@mkdir($path, 0777, true)) {
                    self::$log[] = 'DEBUG: ✓ Carpeta creada: ' . $path;
                    self::$PHOTOS_DIR = $path;
                    return self::$PHOTOS_DIR;
                }
            }
        }
        
        self::$log[] = 'ERROR: No se encontró directorio de fotos escribible';
        return false;
    }
    
    /**
     * En servidor sin cURL, solo usar file_get_contents
     */
    private static function downloadFile($url) {
        self::$log[] = 'Descargando: ' . substr($url, 0, 60) . '...';
        
        if (!ini_get('allow_url_fopen')) {
            self::$log[] = '✗ allow_url_fopen está deshabilitado';
            return false;
        }
        
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 30,
                'user_agent' => 'Mozilla/5.0 (Linux)',
                'follow_location' => 1,
                'max_redirects' => 5,
                'ignore_errors' => true
            ),
            'https' => array(
                'timeout' => 30,
                'user_agent' => 'Mozilla/5.0 (Linux)',
                'follow_location' => 1,
                'max_redirects' => 5,
                'ignore_errors' => true
            )
        ));
        
        $data = @file_get_contents($url, false, $context);
        
        if ($data === false) {
            self::$log[] = '✗ No se pudo descargar';
            return false;
        }
        
        if (strlen($data) < 1) {
            self::$log[] = '✗ Respuesta vacía';
            return false;
        }
        
        self::$log[] = '✓ Descargado: ' . strlen($data) . ' bytes';
        return $data;
    }
    
    /**
     * Obtiene datos de la API
     */
    private static function fetchDataFromAPI() {
        $apiURL = isset($_ENV['GOOGLE_SHEETS_TET_API_URL']) ? $_ENV['GOOGLE_SHEETS_TET_API_URL'] : null;
        
        if (empty($apiURL)) {
            self::$log[] = 'ERROR: GOOGLE_SHEETS_TET_API_URL no configurada en .env';
            return false;
        }
        
        self::$log[] = 'Conectando a API: ' . substr($apiURL, 0, 50) . '...';
        
        $response = self::downloadFile($apiURL);
        
        if ($response === false) {
            self::$log[] = 'ERROR: No se pudo conectar con Google Sheets API';
            return false;
        }
        
        $data = json_decode($response, true);
        
        if (!$data) {
            self::$log[] = 'ERROR: Respuesta JSON inválida';
            return false;
        }
        
        if (!isset($data['success']) || !$data['success']) {
            self::$log[] = 'ERROR: Google Sheets retornó success=false';
            return false;
        }
        
        if (!isset($data['data']) || !is_array($data['data'])) {
            self::$log[] = 'ERROR: No hay datos en respuesta';
            return false;
        }
        
        $total = count($data['data']);
        self::$log[] = 'Datos recibidos: ' . $total . ' registros';
        
        return $data['data'];
    }
    
    /**
     * Convierte URL de Google Drive
     */
    private static function convertGoogleDriveURL($url) {
        if (preg_match('/id=([a-zA-Z0-9-_]+)/', $url, $matches)) {
            return 'https://drive.google.com/uc?export=download&id=' . $matches[1];
        }
        
        if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $url, $matches)) {
            return 'https://drive.google.com/uc?export=download&id=' . $matches[1];
        }
        
        return $url;
    }
    
    /**
     * Valida que sea imagen (por magic bytes)
     */
    private static function isValidImage($photoData) {
        $size = strlen($photoData);
        
        if ($size < 1000) {
            self::$log[] = '    ✗ Muy pequeño: ' . $size . ' bytes';
            return false;
        }
        
        // Verificar magic bytes
        $imageMagic = array(
            'jpeg' => array(0xFF, 0xD8, 0xFF),
            'png' => array(0x89, 0x50, 0x4E, 0x47),
            'gif' => array(0x47, 0x49, 0x46)
        );
        
        foreach ($imageMagic as $format => $bytes) {
            $match = true;
            for ($i = 0; $i < count($bytes); $i++) {
                if (ord($photoData[$i]) != $bytes[$i]) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                self::$log[] = '    ✓ Formato: ' . strtoupper($format);
                return true;
            }
        }
        
        self::$log[] = '    ✗ No es imagen válida (magic bytes no coinciden)';
        return false;
    }
    
    /**
     * Descarga y guarda foto
     */
    private static function downloadAndSavePhoto($googleDriveURL, $qrHash) {
        self::$log[] = '  Descargando foto...';
        
        if (empty($googleDriveURL) || strtolower($googleDriveURL) === 'no') {
            self::$log[] = '    URL vacía, saltando';
            return false;
        }
        
        $photosDir = self::getPhotosDir();
        if ($photosDir === false) {
            self::$log[] = '    ✗ No se pudo obtener directorio';
            return false;
        }
        
        $directDownloadURL = self::convertGoogleDriveURL($googleDriveURL);
        
        $photoData = self::downloadFile($directDownloadURL);
        
        if ($photoData === false) {
            return false;
        }
        
        if (!self::isValidImage($photoData)) {
            return false;
        }
        
        $filename = $photosDir . $qrHash . '.jpg';
        
        $saved = @file_put_contents($filename, $photoData);
        
        if ($saved === false) {
            self::$log[] = '    ✗ Error guardando en: ' . $filename;
            return false;
        }
        
        @chmod($filename, 0644);
        
        self::$log[] = '    ✓ Guardada: ' . $saved . ' bytes';
        return true;
    }
    
    /**
     * Sincroniza desde Google Sheets
     */
    public static function syncFromGoogleSheets() {
        self::$log = array();
        
        self::$log[] = '=== INICIO SINCRONIZACIÓN ===';
        self::$log[] = 'Timestamp: ' . date('Y-m-d H:i:s');
        self::$log[] = 'PHP Version: ' . phpversion();
        self::$log[] = 'Sistema: ' . php_uname();
        self::$log[] = '';
        
        $syncedData = self::fetchDataFromAPI();
        
        if ($syncedData === false) {
            self::$log[] = '=== ERROR: Sincronización fallida ===';
            return array(
                'success' => false,
                'log' => self::$log,
                'total' => 0,
                'inserted' => 0,
                'updated' => 0,
                'qr_generated' => 0,
                'photos' => 0,
                'errors' => 1,
                'skipped' => 0
            );
        }
        
        self::$log[] = '';
        self::$log[] = 'Procesando registros...';
        self::$log[] = '';
        
        $stats = array(
            'inserted' => 0,
            'updated' => 0,
            'qr_generated' => 0,
            'qr_exists' => 0,
            'photos' => 0,
            'skipped' => 0,
            'errors' => 0
        );
        
        $entryTypeMap = array(
            'Estudiante' => 'student',
            'Profesional' => 'professional',
            'Docente' => 'teacher',
            'IEEE' => 'ieee_member'
        );
        
        $recordCount = 0;
        
        foreach ($syncedData as $record) {
            $recordCount++;
            
            if (!isset($record['email_address']) || empty($record['email_address'])) {
                self::$log[] = 'Registro ' . $recordCount . ': Email vacío, saltando';
                $stats['skipped']++;
                continue;
            }
            
            $email = strtolower(trim($record['email_address']));
            
            $fullName = isset($record['full_name']) ? sanitize(trim($record['full_name'])) : 'Sin nombre';
            
            self::$log[] = 'Registro ' . $recordCount . ': ' . $fullName . ' (' . $email . ')';
            
            $entryType = 'student';
            if (isset($record['entry_type'])) {
                $rawType = trim($record['entry_type']);
                foreach ($entryTypeMap as $pattern => $value) {
                    if (stripos($rawType, $pattern) !== false) {
                        $entryType = $value;
                        break;
                    }
                }
            }
            
            $attendeeData = array(
                'full_name' => $fullName,
                'email_address' => $email,
                'phone_number' => isset($record['phone_number']) ? sanitize($record['phone_number']) : '',
                'document_type' => isset($record['document_type']) ? sanitize(trim($record['document_type'])) : '',
                'document_number' => isset($record['document_number']) ? sanitize($record['document_number']) : '',
                'entry_type' => $entryType,
                'payment_confirmed' => 1,
                'photo_uploaded' => !empty($record['photo_url']) ? 1 : 0,
                'photo_file_path' => isset($record['photo_url']) ? sanitize($record['photo_url']) : '',
                'qr_email_sent' => 0,
                'attended_event' => 0,
                'registration_timestamp' => date('Y-m-d H:i:s'),
                'day1' => 0,
                'day1_party' => 0,
                'day2' => 0,
                'day2_party' => 0,
                'day3' => 0,
                'day3_party' => 0
            );
            
            $existing = TetAttendee::where('email_address', $email);
            
            if ($existing === null) {
                self::$log[] = '  Acción: INSERTAR';
                
                $attendeeData['qr_generated'] = 0;
                $attendee = new TetAttendee($attendeeData);
                
                if ($attendee->save()) {
                    self::$log[] = '    ✓ Insertado en BD';
                    $stats['inserted']++;
                    
                    $qrHash = QRGenerator::generateHash($email, $attendee->id);
                    
                    if (!empty($record['photo_url'])) {
                        if (self::downloadAndSavePhoto($record['photo_url'], $qrHash)) {
                            $stats['photos']++;
                        }
                    }
                    
                    $attendee->qr_hash_code = $qrHash;
                    $attendee->qr_generated = 1;
                    
                    if ($attendee->save()) {
                        self::$log[] = '    ✓ QR generado';
                        $stats['qr_generated']++;
                    }
                } else {
                    self::$log[] = '    ✗ Error insertando';
                    $stats['errors']++;
                }
            } else {
                self::$log[] = '  Acción: ACTUALIZAR';
                
                if (!empty($existing->qr_hash_code)) {
                    self::$log[] = '    QR ya existe';
                    $stats['qr_exists']++;
                    $attendeeData['qr_hash_code'] = $existing->qr_hash_code;
                    $attendeeData['qr_generated'] = $existing->qr_generated;
                } else {
                    $attendeeData['qr_generated'] = 0;
                }
                
                $existing->updateProperties($attendeeData);
                
                if ($existing->save()) {
                    self::$log[] = '    ✓ Actualizado';
                    $stats['updated']++;
                    
                    if (empty($existing->qr_hash_code)) {
                        $qrHash = QRGenerator::generateHash($email, $existing->id);
                        
                        if (!empty($record['photo_url'])) {
                            if (self::downloadAndSavePhoto($record['photo_url'], $qrHash)) {
                                $stats['photos']++;
                            }
                        }
                        
                        $existing->qr_hash_code = $qrHash;
                        $existing->qr_generated = 1;
                        
                        if ($existing->save()) {
                            self::$log[] = '    ✓ QR generado';
                            $stats['qr_generated']++;
                        }
                    }
                } else {
                    self::$log[] = '    ✗ Error actualizando';
                    $stats['errors']++;
                }
            }
            
            self::$log[] = '';
        }
        
        self::$log[] = '';
        self::$log[] = '=== RESULTADOS ===';
        self::$log[] = 'Total procesados: ' . count($syncedData);
        self::$log[] = 'Insertados: ' . $stats['inserted'];
        self::$log[] = 'Actualizados: ' . $stats['updated'];
        self::$log[] = 'QR Generados: ' . $stats['qr_generated'];
        self::$log[] = 'QR Existentes: ' . $stats['qr_exists'];
        self::$log[] = 'Fotos descargadas: ' . $stats['photos'];
        self::$log[] = 'Errores: ' . $stats['errors'];
        self::$log[] = 'Saltados: ' . $stats['skipped'];
        self::$log[] = '=== FIN SINCRONIZACIÓN ===';
        
        return array_merge(array(
            'success' => true,
            'total' => count($syncedData),
            'log' => self::$log
        ), $stats);
    }
    
    public static function getLogs() {
        return self::$log;
    }
}
?>