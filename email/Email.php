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
use Dotenv\Dotenv;

// Carga las variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // Si el archivo no existe, no se produce un error

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
        $mail->setFrom($_ENV['EMAIL'], 'Soporte técnico JustiSalud');
        $mail->isHTML(true); // Enviar correo con HTML
        $mail->Subject = $asunto; // Asunto del correo
        $mail->CharSet = 'UTF-8';
        return $mail;
    }

    /**
     * Envía un correo de recuperación de contraseña
     * 
     * @return bool
     */
    public function sendRecoverEmail() : bool {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) return false;

        $mail = $this->createEmail('Renueva tu contraseña de JustiSalud');
        $fullName = "{$this->name}  {$this->lastName}";
        $mail->addAddress($this->email, $fullName);

        // Cargar contenido de la plantilla HTML
        $template = file_get_contents(__DIR__ . '/templates/recuperar.html');
        $link = "https://{$_SERVER['HTTP_HOST']}/recuperarUsuario?token={$this->token}";
        $content = str_replace(['{{NOMBRE}}', '{{ENLACE}}'], [$this->name, $link], $template);
        $mail->Body = $content;

        $mail->AltBody = "JustiSalud\nRenueva tu contraseña de usuario olvidada en JustiSalud.\nDirígete al siguiente enlace para renovar tu contraseña:\n {$link}";
        
        return $this->sendEmail($mail);
    }

    /**
     * Envía un correo de confirmación de registro
     * 
     * @return bool
     */
    public function sendConfirmEmail() : bool {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) return false;
        $mail = $this->createEmail('Confirma tu cuenta de JustiSalud');
        $nombreCompleto = "{$this->name} {$this->lastName}";
        $mail->addAddress($this->email, $nombreCompleto);

        // Cargar contenido de la plantilla HTML
        $template = file_get_contents(__DIR__ . '/templates/registro.html');
        $link = "https://{$_SERVER['HTTP_HOST']}/confirmarRegistro?token={$this->token}";
        $contenido = str_replace(['{{NOMBRE}}', '{{ENLACE}}'], [$this->name, $link], $template);
        $mail->Body = $contenido;

        $mail->AltBody = "JustiSalud\nConfirma tu nueva cuenta en JustiSalud.\nDirígete al siguiente enlace para finalizar el registro de tu cuenta:\n {$link}";
        
        return $this->sendEmail($mail);
    }

    /**
     * Envía un correo de petición de cita
     * 
     * @param string $userId
     * @param string $tel
     * @param string $date
     * @param string $hour
     * @return bool
     */
    public function sendPetitionEmail(string $userId, string $tel, string $date, string $hour) : bool {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) return false;
        $mail = $this->createEmail('Servicio de agendar citas JustiSalud');
        $mail->addAddress('justisaludcol@gmail.com', 'Equipo JustiSalud');

        // Cargar contenido de la plantilla HTML
        $template = file_get_contents(__DIR__ . '/templates/nuevaCita.html');
        $link = "https://{$_SERVER['HTTP_HOST']}/admin?userId= {$userId}";
        $contenido = str_replace(
            ['{{ID}}', '{{NOMBRE}}', '{{APELLIDO}}', '{{CORREO}}', '{{CELULAR}}', '{{FECHA}}', '{{HORA}}', '{{ENLACE}}'],
            [$userId, $this->name, $this->lastName, $this->email, $tel, $date, $hour, $link],
            $template
        );
        $mail->Body = $contenido;

        $mail->AltBody = "JustiSalud\nNueva cita agendada.\nDatos del usuario:\n1. Identificador: {$userId}\n2. Nombres: {$this->name}\n3. Apellidos: {$this->lastName}\n4. Correo: {$this->email}\n5. Celular: {$tel}\n6. Fecha: {$date}\n7. Hora: {$hour}\nAdministrar Citas: {$link}";
        
        return $this->sendEmail($mail);
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
                    error_log("Error al enviar correo a: {$this->email } - Error: " . $error->getMessage(), 3, __DIR__ . '/error_email.log');
                }
            }
        } else {
            // Configuración para producción
            $mail->Host = 'mail.justisalud.com';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            try {
                $mail->send();
                $respuesta = true;
            } catch (Exception $error) {
                error_log("Error al enviar correo a: {$this->email } - Error: " . $error->getMessage(), 3, __DIR__ . '/error_email.log');
            }
        }

        $mail->smtpClose();
        return $respuesta;
    }
}
