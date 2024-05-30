<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        //crear el objeto de email
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASSWORD'];

            //configurar contenido del email
            $mail->setFrom('cuentas@appsalon.com');
            $mail->addAddress('cuentas@appsalon.com', 'appsalon.com');
            $mail->Subject = 'Confirma tu cuenta';

            //Habilitar html
            $mail->isHTML(true);
            $mail->Charset = 'UTF-8';



            //Definir contenido
            $contenido = '<html>';
            $contenido .="<p><strong>Hola ". $this->nombre ."</strong> Has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace</p>";
            $contenido .="<p>Presiona aqui: <a href='". $_ENV['APP_URL']."/confirmar-cuenta?token=". $this->token."'>Confirmar cuenta</a> </p>";
            
            $contenido .= "<p>si tu no solicitastes esta cuena, puedes ignorar el mensaje</p>";
            $contenido .= '</html>';

            
            $mail->Body = $contenido;
           
            if($mail->send()){
                $mensaje = 'mensaje enviado';
            }else{
                $mensaje = 'negativo';
            }
    }

    public function enviarInstrucciones(){
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASSWORD'];

            //configurar contenido del email
            $mail->setFrom('cuentas@appsalon.com');
            $mail->addAddress('cuentas@appsalon.com', 'appsalon.com');
            $mail->Subject = 'Restablece tu passsword';

            //Habilitar html
            $mail->isHTML(true);
            $mail->Charset = 'UTF-8';



            //Definir contenido
            $contenido = '<html>';
            $contenido .="<p><strong>Hola ". $this->nombre ."</strong> has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo. </p>";
            $contenido .="<p>Presiona aqui: <a href='". $_ENV['APP_URL']."/recuperar?token=". $this->token."'>Restablecer passsword</a> </p>";
            $contenido .= "<p>si tu no solicitastes esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .= '</html>';

            
            $mail->Body = $contenido;
           
            if($mail->send()){
                $mensaje = 'mensaje enviado';
            }else{
                $mensaje = 'negativo';
            }
    }
}