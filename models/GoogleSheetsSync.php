<?php 

require_once 'ActiveRecord.php';
require_once 'TetAttendee.php';
require_once 'QRGenerator.php';

class GoogleSheetsSync {
    
    private static $log = array();
    private static $PHOTOS_DIR = null;
    
    /**
     * Obtiene la ruta de carpeta de fotos
     */
    private static function getPhotosDir() {
        if (self::$PHOTOS_DIR !== null) {
            return self::$PHOTOS_DIR;
        }
        
        $possiblePaths = array(
            '/www/ieee/docs/web/build/img/TET/photos/',
            '/tmp/tet_photos/',
            '/var/tmp/tet_photos/'
        );
        
        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            array_unshift($possiblePaths, $_SERVER['DOCUMENT_ROOT'] . '/build/img/TET/photos/');
        }
        
        foreach ($possiblePaths as $path) {
            if (is_dir($path) && is_writable($path)) {
                self::$PHOTOS_DIR = $path;
                return self::$PHOTOS_DIR;
            } elseif (is_dir($path) && @chmod($path, 0777) && is_writable($path)) {
                self::$PHOTOS_DIR = $path;
                return self::$PHOTOS_DIR;
            } elseif (@mkdir($path, 0777, true) && is_writable($path)) {
                self::$PHOTOS_DIR = $path;
                return self::$PHOTOS_DIR;
            }
        }
        
        self::$log[] = 'ERROR: No se encontró directorio de fotos';
        return false;
    }
    
    /**
     * Descarga archivo
     */
    private static function downloadFile($url) {
        if (!ini_get('allow_url_fopen')) {
            return false;
        }
        
        $context = stream_context_create(array(
            'http' => array('timeout' => 30, 'user_agent' => 'Mozilla/5.0'),
            'https' => array('timeout' => 30, 'user_agent' => 'Mozilla/5.0')
        ));
        
        $data = @file_get_contents($url, false, $context);
        
        return ($data !== false && strlen($data) > 0) ? $data : false;
    }
    
    /**
     * Obtiene datos de la API (ahora con missing_photo)
     */
    private static function fetchDataFromAPI() {
        $apiURL = isset($_ENV['GOOGLE_SHEETS_TET_API_URL']) ? $_ENV['GOOGLE_SHEETS_TET_API_URL'] : null;
        
        if (empty($apiURL)) {
            self::$log[] = 'ERROR: GOOGLE_SHEETS_TET_API_URL no configurada';
            return false;
        }
        
        $response = self::downloadFile($apiURL);
        
        if ($response === false) {
            self::$log[] = 'ERROR: No se pudo conectar con Google Sheets';
            return false;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['success']) || !$data['success']) {
            self::$log[] = 'ERROR: Respuesta inválida de API';
            return false;
        }
        
        return array(
            'synced' => isset($data['data']) ? $data['data'] : array(),
            'missing' => isset($data['missing_photo']) ? $data['missing_photo'] : array()
        );
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
     * Valida imagen
     */
    private static function isValidImage($photoData) {
        if (strlen($photoData) < 1000) return false;
        
        $imageMagic = array(
            array(0xFF, 0xD8, 0xFF),
            array(0x89, 0x50, 0x4E, 0x47),
            array(0x47, 0x49, 0x46)
        );
        
        foreach ($imageMagic as $bytes) {
            $match = true;
            for ($i = 0; $i < count($bytes); $i++) {
                if (ord($photoData[$i]) != $bytes[$i]) {
                    $match = false;
                    break;
                }
            }
            if ($match) return true;
        }
        
        return false;
    }
    
    /**
     * Descarga y guarda foto
     */
    private static function downloadAndSavePhoto($googleDriveURL, $qrHash) {
        if (empty($googleDriveURL)) return false;
        
        $photosDir = self::getPhotosDir();
        if ($photosDir === false) return false;
        
        $directDownloadURL = self::convertGoogleDriveURL($googleDriveURL);
        $photoData = self::downloadFile($directDownloadURL);
        
        if ($photoData === false || !self::isValidImage($photoData)) {
            return false;
        }
        
        $filename = $photosDir . $qrHash . '.jpg';
        $saved = @file_put_contents($filename, $photoData);
        
        if ($saved === false) return false;
        
        @chmod($filename, 0644);
        return true;
    }
    
    /**
     * Sincroniza desde Google Sheets
     */
    public static function syncFromGoogleSheets() {
        self::$log = array();
        self::$log[] = '=== INICIO SINCRONIZACIÓN ===';
        
        $apiData = self::fetchDataFromAPI();
        
        if ($apiData === false) {
            return array(
                'success' => false,
                'log' => self::$log,
                'missing_emails' => array()
            );
        }
        
        $syncedData = $apiData['synced'];
        $missingData = $apiData['missing'];
        
        self::$log[] = 'Registros con foto: ' . count($syncedData);
        self::$log[] = 'Registros sin foto: ' . count($missingData);
        self::$log[] = '';
        
        $stats = array(
            'inserted' => 0,
            'updated' => 0,
            'qr_generated' => 0,
            'photos' => 0,
            'errors' => 0
        );
        
        $entryTypeMap = array(
            'Estudiante' => 'student',
            'Profesional' => 'professional',
            'Docente' => 'teacher',
            'IEEE' => 'ieee_member'
        );
        
        // Procesar registros con foto
        foreach ($syncedData as $record) {
            if (!isset($record['email_address']) || empty($record['email_address'])) continue;
            
            $email = strtolower(trim($record['email_address']));
            $fullName = isset($record['full_name']) ? sanitize(trim($record['full_name'])) : 'Sin nombre';
            
            $entryType = 'student';
            
            $attendeeData = array(
                'full_name' => $fullName,
                'email_address' => $email,
                'phone_number' => isset($record['phone_number']) ? sanitize($record['phone_number']) : '',
                'document_type' => isset($record['document_type']) ? sanitize(trim($record['document_type'])) : '',
                'document_number' => isset($record['document_number']) ? sanitize($record['document_number']) : '',
                'entry_type' => $entryType,
                'payment_confirmed' => 1,
                'photo_uploaded' => 1,
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
                $attendeeData['qr_generated'] = 0;
                $attendee = new TetAttendee($attendeeData);
                
                if ($attendee->save()) {
                    $stats['inserted']++;
                    
                    $qrHash = QRGenerator::generateHash($email, $attendee->id);
                    
                    if (!empty($record['photo_url'])) {
                        if (self::downloadAndSavePhoto($record['photo_url'], $qrHash)) {
                            $stats['photos']++;
                        }
                    }
                    
                    $attendee->qr_hash_code = $qrHash;
                    $attendee->qr_generated = 1;
                    $attendee->save();
                    $stats['qr_generated']++;
                } else {
                    $stats['errors']++;
                }
            } else {
                if (empty($existing->qr_hash_code)) {
                    $attendeeData['qr_generated'] = 0;
                } else {
                    $attendeeData['qr_hash_code'] = $existing->qr_hash_code;
                    $attendeeData['qr_generated'] = $existing->qr_generated;
                }
                
                $existing->updateProperties($attendeeData);
                
                if ($existing->save()) {
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
                        $existing->save();
                        $stats['qr_generated']++;
                    }
                } else {
                    $stats['errors']++;
                }
            }
        }
        
        // Extraer solo emails faltantes
        $missingEmails = array();
        foreach ($missingData as $missing) {
            $email = !empty($missing['email_principal']) && $missing['email_principal'] != '(vacío)' 
                ? $missing['email_principal'] 
                : $missing['email_alternativo'];
            
            if ($email && $email != '(vacío)') {
                $missingEmails[] = array(
                    'email' => $email,
                    'name' => isset($missing['full_name']) ? $missing['full_name'] : 'Desconocido'
                );
            }
        }
        
        self::$log[] = '';
        self::$log[] = '=== RESULTADOS ===';
        self::$log[] = 'Insertados: ' . $stats['inserted'];
        self::$log[] = 'Actualizados: ' . $stats['updated'];
        self::$log[] = 'Fotos descargadas: ' . $stats['photos'];
        self::$log[] = 'Errores: ' . $stats['errors'];
        self::$log[] = 'Faltantes: ' . count($missingEmails);
        
        return array_merge(array(
            'success' => true,
            'total' => count($syncedData),
            'log' => self::$log,
            'missing_emails' => $missingEmails
        ), $stats);
    }
    
    public static function getLogs() {
        return self::$log;
    }
}
?>