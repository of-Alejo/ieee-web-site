<?php

require_once '../models/TetAdmin.php';
class Main {
    public static function index($router) {
        // Para la url '/'
        $router->render('main/index', array(
            'title' => '',
            'metaTitle' => 'Página principal Rama IEEE Unicauca',
            'descriptionSite' => 'El IEEE es una organización sin ánimo de lucro y es considerada la mayor asociación del mundo para el desarrollo tecnológico.'
        ));
    }

    public static function events($router) {
        // Para la url '/eventos'
        $router->render('main/events', array(
            'title' => '- Eventos',
            'metaTitle' => 'Eventos - Rama IEEE Unicauca',
            'descriptionSite' => '¿Qué hacemos? Entérate de todos los eventos que realizamos en nuestro recorrido.'
        ));
    }

    public static function tet_2025($router) {
        // Para la url '/tet'
        $router->render('main/tet/tet_2025', array(
            'title' => '- TET 2025',
            'metaTitle' => 'TET 2025 - Rama IEEE Unicauca',
            'descriptionSite' => 'El TET 2025 es un evento organizado por la Rama IEEE Unicauca, donde se busca fomentar el desarrollo tecnológico y la innovación en la región.'
        ));
    }

    public static function tet_2025_ingreso($router) {
        startSession();
        $viewData = array(
            'title' => '- Staff',
            'metaTitle' => 'TET 2025 - Staff',
            'descriptionSite' => 'Zona exclusiva para el control de ingreso al evento.',
            'content_state' => 'login',
            'login_error' => null,
            'scan_error' => null,
            'attendee' => null
        );
        
        $remoteIP = $_SERVER['REMOTE_ADDR'];
        
        // ============================================
        // MANEJO POST - LOGIN (RESPONDE JSON)
        // ============================================
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userToValidate = new TetAdmin($_POST);
            
            // Guard: Validaciones del formulario
            if (!$userToValidate->isValidLoginFields()) {
                header('Content-Type: application/json');
                echo json_encode(array(
                    'success' => false,
                    'error' => TetAdmin::getAlerts('error')
                ));
                return;
            }
            
            // Guard: Buscar usuario
            $userInDB = TetAdmin::where('username', $userToValidate->username);
            if ($userInDB == NULL) {
                error_log("TET login fallido: usuario '{$userToValidate->username}' no existe desde IP: {$remoteIP}");
                sleep(2);
                
                header('Content-Type: application/json');
                echo json_encode(array(
                    'success' => false,
                    'error' => 'El usuario no existe en el sistema.'
                ));
                return;
            }
            
            // Guard: Verificar contraseña
            if (!$userInDB->checkPassword($userToValidate->pwd)) {
                error_log("TET login fallido: contraseña incorrecta para '{$userToValidate->username}' desde IP: {$remoteIP}");
                sleep(2);
                
                header('Content-Type: application/json');
                echo json_encode(array(
                    'success' => false,
                    'error' => 'Credenciales incorrectas. Verifica tu usuario y contraseña.'
                ));
                return;
            }
            
            // Login exitoso
            $_SESSION['is_validator'] = true;
            $_SESSION['validator_login_time'] = time();
            $_SESSION['validator_username'] = $userInDB->username;
            $_SESSION['validator_id'] = $userInDB->id;
            
            if (function_exists('session_regenerate_id')) {
                session_regenerate_id(true);
            }
            
            error_log("TET login exitoso: {$userInDB->username} (ID: {$userInDB->id}) desde IP: {$remoteIP}");
            
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => true,
                'redirect' => '/tet/ingreso',
                'message' => 'Acceso concedido correctamente'
            ));
            return;
        }
        
        // ============================================
        // MANEJO GET - VISTAS
        // ============================================
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $isValidator = isset($_SESSION['is_validator']) && $_SESSION['is_validator'] === true;
            
            // Guard: Verificar sesión expirada
            if ($isValidator && isset($_SESSION['validator_login_time'])) {
                $sessionTimeout = 2 * 60 * 60;
                
                if ((time() - $_SESSION['validator_login_time']) > $sessionTimeout) {
                    session_unset();
                    session_destroy();
                    $isValidator = false;
                    
                    $viewData['login_error'] = 'Tu sesión ha expirado. Inicia sesión nuevamente.';
                    $router->render('main/tet/tet_2025_ingreso', $viewData);
                    return;
                }
            }
            
            // Guard: No autenticado
            if (!$isValidator) {
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            // Autenticado - Vista scan por defecto
            $viewData['content_state'] = 'scan';
            
            // Guard: No hay código QR
            if (empty($_GET['code'])) {
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            // Validar y procesar código QR
            $qrCode = sanitize($_GET['code']);
            
            // Guard: Formato inválido
            if (strlen($qrCode) !== 64 || !ctype_xdigit($qrCode)) {
                $viewData['scan_error'] = 'Formato inválido (64 caracteres hexadecimales requeridos).';
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            // Buscar asistente
            $attendees = TetAttendee::where('qr_hash_code', $qrCode);
            
            // Guard: Código no encontrado
            if (empty($attendees) || !is_array($attendees)) {
                $viewData['scan_error'] = 'Código QR no válido o no encontrado.';
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            // Código válido
            $viewData['attendee'] = $attendees[0];
            $viewData['content_state'] = 'verify';
            $router->render('main/tet/tet_2025_ingreso', $viewData);
            return;
        }
        
        // ============================================
        // MÉTODO NO PERMITIDO
        // ============================================
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Método no permitido'));
    }


    public static function year_book($router) {
        // Para la url '/anuario'
        $router->render('main/year_book', array(
            'title' => '- Anuario',
            'metaTitle' => 'Anuario - Rama IEEE Unicauca',
            'descriptionSite' => 'El anuario es un documento que recopila las actividades y logros de la Rama IEEE Unicauca durante el año.'
        ));
    }

    public static function gallery($router) {
        // Para la url '/galeria'
        $router->render('main/galery', array(
            'title' => '- Galería',
            'metaTitle' => 'Galería - Rama IEEE Unicauca',
            'descriptionSite' => 'En nuestra galería podrás encontrar fotos y videos de nuestros eventos y actividades.'
        ));
    }

    public static function organization($router) {
        // Para la url '/organizacion'
        $router->render('main/organization', array(
            'title' => '- Organización',
            'metaTitle' => 'Organización - Rama IEEE Unicauca',
            'descriptionSite' => 'Conoce a los miembros de la Rama IEEE Unicauca y su trayectoria en el desarrollo tecnológico.'
        ));
    }
}