<?php

require_once '../models/TetAdmin.php';
require_once '../models/TetAttendee.php';
require_once '../models/GoogleSheetsSync.php';

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

    public static function tet_2025_ingreso_test($router) {
        $router->render('main/tet/testhash', array(
            'title' => '- Sincronización',
            'metaTitle' => 'TET 2025 - Zona exclusiva para visualización de invitados.',
            'descriptionSite' => 'Zona exclusiva para el control de ingreso al evento.'
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
            'attendee' => null,
            'photo_path' => null
        );
        
        // ============================================
        // POST - MANEJAR FORMULARIOS
        // ============================================
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // CASO 1: Registro de ingreso (formulario de evento) - PRIMERO
            if (isset($_POST['register_entry'])) {
                require_once __DIR__ . '/../models/TetAttendee.php';
                
                $email = sanitize($_POST['email_address']);
                $eventDay = sanitize($_POST['event_day']);
                TetAttendee::registerEntry($email, $eventDay);
                header('Location: /tet/ingreso');
                exit;
            }
            
            // CASO 2: Login (formulario de autenticación) - SEGUNDO
            if (isset($_POST['username']) && isset($_POST['pwd'])) {
                $userToValidate = new TetAdmin($_POST);
                
                if (!$userToValidate->isValidLoginFields()) {
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        'success' => false,
                        'error' => TetAdmin::getAlerts('error')
                    ));
                    return;
                }
                
                $userInDB = TetAdmin::where('username', $userToValidate->username);
                
                if ($userInDB == NULL) {
                    sleep(2);
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        'success' => false,
                        'error' => 'El usuario no existe en el sistema.'
                    ));
                    return;
                }
                
                if (!$userInDB->checkPassword($userToValidate->pwd)) {
                    sleep(2);
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        'success' => false,
                        'error' => 'Credenciales incorrectas. Verifica tu usuario y contraseña.'
                    ));
                    return;
                }
                
                $_SESSION['is_validator'] = true;
                $_SESSION['validator_login_time'] = time();
                $_SESSION['validator_id'] = $userInDB->id;
                
                if (function_exists('session_regenerate_id')) {
                    session_regenerate_id(true);
                }
                
                header('Content-Type: application/json');
                echo json_encode(array(
                    'success' => true,
                    'redirect' => '/tet/ingreso'
                ));
                return;
            }
        }


        // ============================================
        // GET - VISTAS
        // ============================================
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $isValidator = isset($_SESSION['is_validator']) && $_SESSION['is_validator'] === true;
            
            // Verificar sesión expirada
            if ($isValidator && isset($_SESSION['validator_login_time'])) {
                if ((time() - $_SESSION['validator_login_time']) > 7200) { // 2h
                    session_unset();
                    session_destroy();
                    $isValidator = false;
                    $viewData['login_error'] = 'Tu sesión ha expirado. Inicia sesión nuevamente.';
                }
            }
            
            if (!$isValidator) {
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            $viewData['content_state'] = 'scan';
            
            if (empty($_GET['code'])) {
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            $qrCode = sanitize($_GET['code']);
            
            if (strlen($qrCode) !== 64 || !ctype_xdigit($qrCode)) {
                $viewData['scan_error'] = 'Formato inválido (64 caracteres hexadecimales requeridos).';
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            require_once __DIR__ . '/../models/TetAttendee.php';
            $attendee = TetAttendee::where('qr_hash_code', $qrCode);
            
            if (empty($attendee)) {
                $viewData['scan_error'] = 'Código QR no válido o no encontrado.';
                $router->render('main/tet/tet_2025_ingreso', $viewData);
                return;
            }
            
            // Construir ruta de la foto
            $photoURL = '/build/img/TET/photos/' . $attendee->qr_hash_code . '.jpg';
            
            $viewData['attendee'] = $attendee;
            $viewData['photo_path'] = $photoURL;
            $viewData['content_state'] = 'verify';
            $router->render('main/tet/tet_2025_ingreso', $viewData);
            return;
        }
        
        // Método no permitido
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Método no permitido'));
    }

    public static function tet_2025_attendees($router) {
        startSession();
        
        $viewData = array(
            'title' => '- Asistentes',
            'metaTitle' => 'TET 2025 - Asistentes',
            'descriptionSite' => 'Lista de todos los asistentes registrados',
            'attendees' => array()
        );
        
        // Guard: Verificar sesión
        $isValidator = isset($_SESSION['is_validator']) && $_SESSION['is_validator'] === true;
        
        if (!$isValidator) {
            header('Location: /tet/ingreso');
            exit;
        }
        
        $attendeesDB = TetAttendee::all();
        
        $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        
        $attendees = array();
        foreach ($attendeesDB as $attendee) {
            $attendee->qr_url = QRGenerator::getQRFromHash($attendee->qr_hash_code, $baseURL);
            $attendee->photo_path = '/build/img/TET/photos/' . $attendee->qr_hash_code . '.jpg';
            $attendees[] = $attendee;
        }
        
        $viewData['attendees'] = $attendees;
        $viewData['total'] = count($attendees);
        
        $router->render('main/tet/tet_2025_attendees', $viewData);
    }


    public static function sync_google_sheets($router) {
        startSession();
        
        // Guard: Solo admin puede sincronizar
        $isValidator = isset($_SESSION['is_validator']) && $_SESSION['is_validator'] === true;
        
        if (!$isValidator) {
            header('Location: /tet/ingreso');
            return;
        }
        
        // Ejecutar sincronización
        $result = GoogleSheetsSync::syncFromGoogleSheets();
        
        // Renderizar vista con resultados
        $router->render('main/tet/sync_results', array(
            'title' => '- Sincronización',
            'metaTitle' => 'TET 2025 - Zona exclusiva para visualización de invitados.',
            'descriptionSite' => 'Zona exclusiva para el control de ingreso al evento.',
            'result' => $result
        ));
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