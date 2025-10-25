<?php
require_once 'ActiveRecord.php';

class TetAttendee extends ActiveRecord {
    protected static $table = 'tet_attendees';
    protected static $dbColumns = array(
        'id', 'full_name', 'email_address', 'phone_number', 'entry_type', 'payment_confirmed',
        'photo_uploaded', 'photo_file_path', 'qr_hash_code', 'qr_generated',
        'qr_email_sent', 'email_request_sent', 'attended_event',
        'entry_timestamp', 'registration_timestamp', 'last_updated_timestamp'
    );

    public $id;
    public $full_name;
    public $email_address;
    public $phone_number;
    public $entry_type;
    public $payment_confirmed;
    public $photo_uploaded;
    public $photo_file_path;
    public $qr_hash_code;
    public $qr_generated;
    public $qr_email_sent;
    public $email_request_sent;
    public $attended_event;
    public $entry_timestamp;
    public $registration_timestamp;
    public $last_updated_timestamp;

    public function __construct($args = array()) {
        foreach (static::$dbColumns as $col) {
            if($col == "id" && $this->id==null) continue;
            $this->$col = isset($args[$col]) ? sanitize($args[$col]) : null;
        }
    }
}
?>
