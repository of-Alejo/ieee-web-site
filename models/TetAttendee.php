<?php
require_once 'ActiveRecord.php';

class TetAttendee extends ActiveRecord {
    protected static $table = 'tet_attendees';
    protected static $dbColumns = array(
        'id', 'full_name', 'email_address', 'phone_number', 
        'document_type', 'document_number', 'entry_type', 
        'payment_confirmed', 'photo_uploaded', 'photo_file_path', 
        'qr_hash_code', 'qr_generated', 'qr_email_sent', 
        'email_request_sent', 
        'day1', 'day1_party', 'day2', 'day2_party', 'day3', 'day3_party',
        'registration_timestamp', 'last_updated_timestamp'
    );

    public $id;
    public $full_name;
    public $email_address;
    public $phone_number;
    public $document_type;
    public $document_number;
    public $entry_type;
    public $payment_confirmed;
    public $photo_uploaded;
    public $photo_file_path;
    public $qr_hash_code;
    public $qr_generated;
    public $qr_email_sent;
    public $email_request_sent;
    public $day1;
    public $day1_party;
    public $day2;
    public $day2_party;
    public $day3;
    public $day3_party;
    public $registration_timestamp;
    public $last_updated_timestamp;

    public function __construct($args = array()) {
        foreach (static::$dbColumns as $col) {
            if($col == "id" && $this->id==null) continue;
            $this->$col = isset($args[$col]) ? sanitize($args[$col]) : null;
        }
    }
    /**
     * Registrar ingreso a un evento específico
     * Incrementa el contador del día/evento para este asistente
     * 
     * @param string $email Email del asistente
     * @param string $eventDay Tipo de evento (day1, day1_party, day2, etc.)
     * @return bool True si se registró, False si falló
     */
    public static function registerEntry($email, $eventDay) {
        $validEvents = array('day1', 'day1_party', 'day2', 'day2_party', 'day3', 'day3_party');
        
        // Validar que el evento existe
        if (!in_array($eventDay, $validEvents)) {
            return false;
        }
        
        // Buscar al asistente por email
        $attendee = self::where('email_address', $email);
        
        if (empty($attendee)) {
            return false;
        }
        
        // Incrementar el contador del evento
        $query = "UPDATE " . self::$table . " SET {$eventDay} = {$eventDay} + 1 WHERE email_address = :email";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':email', $email);
        
        return $stmt->execute();
    }

}
?>
