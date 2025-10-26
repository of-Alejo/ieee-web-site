<?php

require_once 'ActiveRecord.php';

class TetAdmin extends ActiveRecord {
    protected static $table = 'tet_admin';
    protected static $dbColumns = array('id', 'username', 'pwd');
    
    public $id;
    public $username;
    public $pwd;
    
    public function __construct($args = array()) {
        $this->id = isset($args['id']) ? (int)$args['id'] : null;
        $this->username = isset($args['username']) ? trim(htmlspecialchars($args['username'], ENT_QUOTES, 'UTF-8')) : null;
        $this->pwd = isset($args['password']) ? $args['password'] : (isset($args['pwd']) ? $args['pwd'] : null);
    }
    
    public function isValidLoginFields() {
        self::$alerts = array();
        
        if (empty($this->username)) {
            self::setAlert('error', 'El nombre de usuario es obligatorio');
        } elseif (strlen($this->username) < 3 || strlen($this->username) > 50) {
            self::setAlert('error', 'Usuario debe tener entre 3 y 50 caracteres');
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $this->username)) {
            self::setAlert('error', 'Usuario contiene caracteres no permitidos');
        }
        
        if (empty($this->pwd)) {
            self::setAlert('error', 'La contraseña es obligatoria');
        } elseif (strlen($this->pwd) < 6 || strlen($this->pwd) > 100) {
            self::setAlert('error', 'Contraseña debe tener entre 6 y 100 caracteres');
        }
        
        return empty(self::$alerts['error']);
    }
    
    public function hashPassword() {
        $this->pwd = md5($this->pwd);
    }
    
    public function checkPassword($password) {
        if (empty($password) || empty($this->pwd)) {
            return false;
        }
        
        $isBcrypt = function_exists('password_verify') && substr($this->pwd, 0, 4) === '$2y$';
        
        if ($isBcrypt) {
            return password_verify($password, $this->pwd);
        }
        
        $hashedPassword = md5($password);
        return self::constantTimeCompare($this->pwd, $hashedPassword);
    }
    
    private static function constantTimeCompare($known, $user) {
        if (strlen($known) !== strlen($user)) {
            return false;
        }
        
        $result = 0;
        for ($i = 0; $i < strlen($known); $i++) {
            $result |= ord($known[$i]) ^ ord($user[$i]);
        }
        
        return $result === 0;
    }
    
    public static function getAlerts($type = 'error') {
        if (!isset(self::$alerts[$type])) {
            return null;
        }
        return !empty(self::$alerts[$type]) ? implode('. ', self::$alerts[$type]) : null;
    }
}
