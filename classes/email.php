<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;
    public function __construct($email,$nombre,$token)
    {
        $this->email=$email;
        $this->nombre=$nombre;
        $this->token=$token;
    }

    public function enviarConfirmacion(){
        //Crear el objeto de email
        $mail= new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'd98386aff043cd';
        $mail->Password = 'ad9a6874b4b037';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com','AppSalon.com');
        $mail->Subject='Confirmacion de cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';
        $contenido="<html>";
        $contenido.="<p><strong>Hola ". $this->nombre. "</strong> has creado tu cuenta solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido.="<p>Presiona aqui: <a href='http://". $_SERVER["HTTP_HOST"] . "/confirmar-cuenta?token=".$this->token."'>Confirmar Cuenta</a> </p>";
        $contenido.="<p>Si tu no solicitaste esta cuenta, ignora el mensaje</p>";
        $contenido.="</html>";
        $mail->Body=$contenido;

        //Enviar
        $mail->send();
   
    }
    public function enviarInstrucciones(){
        //Crear el objeto de email
        $mail= new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'd98386aff043cd';
        $mail->Password = 'ad9a6874b4b037';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com','AppSalon.com');
        $mail->Subject='Reestablecer contrasela';

        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';
        $contenido="<html>";
        $contenido.="<p><strong>Hola ". $this->nombre. "</strong> Has solicitado reestablecer tu contraseña, presiona el siguiente enlace para hacerlo.</p>";
        $contenido.="<p>Presiona aqui: <a href='http://appsalon.test/recuperar?token=".$this->token."'>Reestablecer contraseña</a> </p>";
        $contenido.="<p>Si tu no solicitaste este cambio, ignora el mensaje</p>";
        $contenido.="</html>";
        $mail->Body=$contenido;

        //Enviar
        $mail->send();
   
    }

    public static function enviarRazon($nombre,$razon){
        //Crear el objeto de email
        $mail= new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'd98386aff043cd';
        $mail->Password = 'ad9a6874b4b037';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com','AppSalon.com');
        $mail->Subject='Reestablecer contrasela';

        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';
        $contenido="<html>";
        $contenido.="<p><strong>Hola ". $nombre. "</strong> Hemos eliminado tu cita por el siguiente motivo:</p>";
        $contenido.="<p ".$razon. "</p>";
        $contenido.="</html>";
        $mail->Body=$contenido;

        //Enviar
        $mail->send();
   
    }
}