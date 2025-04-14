<?php

/*
    EMAIL    
    Esta es una clase donde se maneja el envío de correos electrónicos.
    Las plantillas de los correos se encuentran en la carpeta de templates, 
    y las credenciales en el entorno env.
*/

namespace Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {
    private string $name;
    private string $lastName;
    private string $email;
    private string $token;

    /**
     * Constructor de la clase Email
     * 
     * @param string $nombre
     * @param string $apellido
     * @param string $email
     * @param string $token
     */
    public function __construct(string $name, string $lastName, string $email, string $token) {
        $this->name = $name;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Configura y devuelve un objeto PHPMailer preconfigurado
     * 
     * @param string $asunto
     * @return PHPMailer
     */
    private function createEmail(string $asunto) : PHPMailer {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL'];
        $mail->Password = $_ENV['PASSWORD'];
        $mail->setFrom($_ENV['EMAIL'], '');
        $mail->isHTML(true); // Enviar correo con HTML
        $mail->Subject = $asunto; // Asunto del correo
        $mail->CharSet = 'UTF-8';
        return $mail;
    }

    /**
     * Envía el correo utilizando PHPMailer y maneja la configuración de acuerdo al entorno
     * 
     * @param PHPMailer $mail
     * @return bool
     */
    private function sendEmail(PHPMailer $mail) : bool {
        $respuesta = false;
        if (!isProduction()) {
            // Configuración para desarrollo
            $mail->SMTPDebug = 0;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $servidores = ['smtp.office365.com', 'smtp.gmail.com'];

            foreach ($servidores as $servidor) {
                try {
                    $mail->Host = $servidor;
                    $mail->send();
                    $respuesta = true;
                    break;
                } catch (Exception $error) {
                    error_log("Error al enviar correo a: {$this->email } - Error: " . $error->getMessage(), 3, __DIR__ . '/../logs/error_email.log');
                }
            }
        } else {
            // Configuración para producción
            $mail->Host = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            try {
                $mail->send();
                $respuesta = true;
            } catch (Exception $error) {
                error_log("Error al enviar correo a: {$this->email } - Error: " . $error->getMessage(), 3, __DIR__ . '/../logs/error_email.log');
            }
        }

        $mail->smtpClose();
        return $respuesta;
    }
}
