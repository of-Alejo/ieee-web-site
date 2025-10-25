<?php

require_once 'ActiveRecord.php';
/**
 * Modelo TetAdmin
 * Representa un administrador/validador del evento TET 2025
 * Compatible con PHP 5.3
 * 
 * @table tet_admin
 * @columns id, username, pwd
 */
class TetAdmin extends ActiveRecord {
    // Configuración
    protected static $table = 'tet_admin';
    protected static $dbColumns = array('id', 'username', 'pwd');
    
    // Atributos
    public $id;
    public $username;
    public $pwd;
    
    /**
     * Constructor compatible con PHP 5.3
     * 
     * @param array $args Datos del formulario
     */
    public function __construct($args = array()) {
        $this->id = isset($args['id']) ? (int)$args['id'] : null;
        $this->username = isset($args['username']) ? $this->sanitizeValue($args['username']) : null;
        
        // Manejar tanto 'password' como 'pwd'
        $this->pwd = isset($args['password']) 
            ? $args['password'] 
            : (isset($args['pwd']) ? $args['pwd'] : null);
    }
    
    /**
     * Validar campos para login
     * 
     * @return bool True si válido, False si hay errores
     */
    public function isValidLoginFields() {
        // Limpiar alertas previas
        if (!isset(self::$alerts)) {
            self::$alerts = array();
        } else {
            self::$alerts = array();
        }
        
        // Validar username
        if (empty($this->username)) {
            self::setAlert('error', 'El nombre de usuario es obligatorio');
        } elseif (strlen($this->username) < 3 || strlen($this->username) > 50) {
            self::setAlert('error', 'Usuario debe tener entre 3 y 50 caracteres');
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $this->username)) {
            self::setAlert('error', 'Usuario contiene caracteres no permitidos');
        }
        
        // Validar contraseña
        if (empty($this->pwd)) {
            self::setAlert('error', 'La contraseña es obligatoria');
        } elseif (strlen($this->pwd) < 6) {
            self::setAlert('error', 'Contraseña debe tener al menos 6 caracteres');
        } elseif (strlen($this->pwd) > 100) {
            self::setAlert('error', 'Contraseña es demasiado larga');
        }
        
        // Retornar true si no hay errores
        $alerts = isset(self::$alerts['error']) ? self::$alerts['error'] : array();
        return empty($alerts);
    }
    
    /**
     * Hashear la contraseña
     * Usa password_hash si está disponible (PHP >= 5.5), sino md5
     */
    public function hashPassword() {
        $this->pwd = md5($this->pwd);
    }
    
    /**
     * Verificar contraseña
     * 
     * @param string $password Contraseña en texto plano
     * @return bool True si coincide, False si no
     */
    public function checkPassword($password) {
        // Guard: Valores vacíos
        if (empty($password) || empty($this->pwd)) {
            return false;
        }
        // Detectar si es bcrypt (formato: $2y$ o $2a$ o $2b$)
        $isBcrypt = function_exists('password_verify') && (
            substr($this->pwd, 0, 4) === '$2y$' || 
            substr($this->pwd, 0, 4) === '$2a$' || 
            substr($this->pwd, 0, 4) === '$2b$'
        );
        
        if ($isBcrypt) {
            return password_verify($password, $this->pwd);
        }
        
        
        // Fallback MD5 con hash_equals (previene timing attacks)
        $hashedPassword = md5($password);
        // PHP 5.3: implementar hash_equals manualmente
        return self::constantTimeCompare($this->pwd, $hashedPassword);
    }
    
    /**
     * Comparación de strings a tiempo constante (para PHP < 5.6)
     * Previene timing attacks
     * 
     * @param string $known El valor esperado
     * @param string $user El valor del usuario
     * @return bool
     */
    private static function constantTimeCompare($known, $user) {
        if (strlen($known) !== strlen($user)) {
            return false;
        }
        
        $result = 0;
        $length = strlen($known);
        
        for ($i = 0; $i < $length; $i++) {
            $result |= ord($known[$i]) ^ ord($user[$i]);
        }
        
        return $result === 0;
    }
    
    /**
     * Sanitizar valores de entrada
     * 
     * @param mixed $value
     * @return mixed Valor sanitizado
     */
    protected function sanitizeValue($value) {
        if (is_string($value)) {
            return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }
        return $value;
    }
    
    /**
     * Obtener alertas de un tipo específico
     * 
     * @param string $type Tipo de alerta ('error', 'success', 'warning')
     * @return string|null Alertas concatenadas o null
     */
    public static function getAlerts($type = 'error') {
        if (!isset(self::$alerts[$type])) {
            return null;
        }
        
        $alerts = self::$alerts[$type];
        return !empty($alerts) ? implode('. ', $alerts) : null;
    }
}
