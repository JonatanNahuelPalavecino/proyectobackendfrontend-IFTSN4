<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';


function sendMailNotify( array $destinatario, string $asunto, string $mensaje):bool
{

    // PARAMETROS A CONFIGURAR DE CUENTA HOST
    $mailHost = 'smtp.gmail.com';
    $mailUsername = 'antruxxi@gmail.com';
    $mailPassword= 'ltvyglwbjampvbvz'; //Contraseña de aplicacion. No de mail
    $mailPort = 587;
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    //Desestructuramos los datos de la session.
    $name = $destinatario['nombre'];
    $email = $destinatario['email'];

    try {
        // CONFIGURACION DEL SERVIDOR
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth   = true;
        $mail->Host       = $mailHost;
        $mail->Username   = $mailUsername;
        $mail->Password   = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $mailPort;                  
                                                                        
        // REMITENTE Y DESTINATARIO
    
        // Usamos el correo del SISTEMA como remitente.
        $mail->setFrom($mailUsername, 'Sistema de reservas');
        //Destinatario (A QUIEN VA EL MAIL )
        $mail->addAddress($email, $name);
        //Reply-To : el correo del visitante
        //Asi si respondes el correo, va directo al usuario
        // $mail->addReplyTo($email, $name);
        //Permitimos formato HTML
        $mail->isHTML(true);              
        // ASUNTO
        $mail->Subject = $asunto;
        // CUERPO DEL MENSAJE EN HTML
        $mail->Body = $mensaje;
    
        //Cuerpo en texto plano (para clientes que no soportan HTML)    
        $mail->AltBody = strip_tags($mensaje);
    
        // ENVIAR CORREO
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        echo 'Error al enviar aviso:'. $mail->ErrorInfo;
        return false;
    }
}


?>