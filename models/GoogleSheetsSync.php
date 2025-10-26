<?php

require_once 'ActiveRecord.php';
require_once 'TetAttendee.php';
require_once 'QRGenerator.php';

class GoogleSheetsSync {
    
    private static $log = array();
    private static $PHOTOS_DIR = '../web/build/img/TET/photos/';
    
    private static function fetchDataFromAPI() {
        $apiURL = isset($_ENV['GOOGLE_SHEETS_TET_API_URL']) ? $_ENV['GOOGLE_SHEETS_TET_API_URL'] : null;

        if (empty($apiURL)) {
            self::$log[] = 'ERROR: GOOGLE_SHEETS_TET_API_URL no configurada';
            return false;
        }
        
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 2000,
                'user_agent' => 'TET-Sync/1.0',
                'ignore_errors' => true
            )
        ));
        
        $response = @file_get_contents($apiURL, false, $context);

        if ($response === false) {
            self::$log[] = 'ERROR: No se pudo conectar con la API';
            return false;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['success']) || !$data['success']) {
            self::$log[] = 'ERROR: Respuesta inválida de la API';
            return false;
        }
        
        self::$log[] = 'Datos recibidos: ' . $data['total'] . ' registros';
        return $data['data'];
    }
    
    private static function convertGoogleDriveURL($url) {
        if (preg_match('/id=([a-zA-Z0-9-_]+)/', $url, $matches)) {
            return 'https://drive.google.com/uc?export=download&id=' . $matches[1];
        }
        return $url;
    }
    
    private static function downloadAndSavePhoto($googleDriveURL, $qrHash) {
        self::$log[] = 'Intentando descargar foto GoogleDrive: ' . $googleDriveURL . ' | hash: ' . $qrHash;
        
        if (empty($googleDriveURL) || $googleDriveURL === 'No') {
            self::$log[] = '  ✗ URL vacía o NO';
            return false;
        }

        // 1. Verificar existencia carpeta
        if (!is_dir(self::$PHOTOS_DIR)) {
            self::$log[] = '  Carpeta no existe. Intentando crear: ' . self::$PHOTOS_DIR;
            $created = mkdir(self::$PHOTOS_DIR, 0755, true);
            if ($created) {
                self::$log[] = '  ✓ Carpeta creada: ' . self::$PHOTOS_DIR;
            } else {
                self::$log[] = '  ✗ No se pudo crear la carpeta: ' . self::$PHOTOS_DIR;
                self::$log[] = '  Verifica permisos de escritura webserver en: ' . dirname(self::$PHOTOS_DIR);
                return false;
            }
        } else {
            self::$log[] = '  Carpeta ya existe: ' . self::$PHOTOS_DIR;
        }

        // 2. Descargar
        $directDownloadURL = self::convertGoogleDriveURL($googleDriveURL);
        self::$log[] = '  Descargando desde: ' . $directDownloadURL;
        
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 30,
                'user_agent' => 'Mozilla/5.0',
                'ignore_errors' => true
            )
        ));

        $photoData = @file_get_contents($directDownloadURL, false, $context);

        if ($photoData === false) {
            self::$log[] = '  ✗ Error al descargar la foto: file_get_contents=false';
            return false;
        }
        if (strlen($photoData) < 100) {
            self::$log[] = '  ✗ Archivo descargado demasiado pequeño: ' . strlen($photoData) . ' bytes';
            return false;
        }

        // 3. Guardar archivo
        $filename = self::$PHOTOS_DIR . $qrHash . '.jpg';
        $saved = file_put_contents($filename, $photoData);

        if ($saved !== false) {
            self::$log[] = '  ✓ Foto guardada: ' . $filename . ' (' . $saved . ' bytes)';
            return true;
        } else {
            self::$log[] = '  ✗ No se pudo guardar la foto en: ' . $filename;
            self::$log[] = '  Verifica permisos de escritura webserver en la ruta.';
            return false;
        }
    }

    
    public static function syncFromGoogleSheets() {
        self::$log = array();
        
        $syncedData = self::fetchDataFromAPI();
        
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
        
        foreach ($syncedData as $record) {
            $email = strtolower(trim($record['email_address']));
            
            if (empty($email)) {
                $stats['skipped']++;
                continue;
            }
            
            $entryType = 'student';
            $rawType = isset($record['entry_type']) ? trim($record['entry_type']) : '';
            
            foreach ($entryTypeMap as $pattern => $value) {
                if (stripos($rawType, $pattern) !== false) {
                    $entryType = $value;
                    break;
                }
            }
            
            $attendeeData = array(
                'full_name' => sanitize(trim($record['full_name'])),
                'email_address' => $email,
                'phone_number' => sanitize($record['phone_number']),
                'document_type' => sanitize(trim($record['document_type'])),
                'document_number' => sanitize($record['document_number']),
                'entry_type' => $entryType,
                'payment_confirmed' => 1,
                'photo_uploaded' => !empty($record['photo_url']) ? 1 : 0,
                'photo_file_path' => sanitize($record['photo_url']),
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
                // INSERTAR
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
                    
                    if ($attendee->save()) {
                        $stats['qr_generated']++;
                    }
                } else {
                    $stats['errors']++;
                }
            } else {
                // ACTUALIZAR
                if (!empty($existing->qr_hash_code)) {
                    $stats['qr_exists']++;
                    $attendeeData['qr_hash_code'] = $existing->qr_hash_code;
                    $attendeeData['qr_generated'] = $existing->qr_generated;
                } else {
                    $attendeeData['qr_generated'] = 0;
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
                        
                        if ($existing->save()) {
                            $stats['qr_generated']++;
                        }
                    }
                } else {
                    $stats['errors']++;
                }
            }
        }
        
        self::$log[] = 'Total: ' . count($syncedData);
        self::$log[] = 'Insertados: ' . $stats['inserted'];
        self::$log[] = 'Actualizados: ' . $stats['updated'];
        self::$log[] = 'QR Generados: ' . $stats['qr_generated'];
        self::$log[] = 'Fotos: ' . $stats['photos'];
        
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
