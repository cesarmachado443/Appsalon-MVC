<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;


class LoginController{
    
    public static function login(Router $router){
        
            $auth = new Usuario();
        if($_SERVER['REQUEST_METHOD']  === 'POST'){
        $auth = new Usuario($_POST);

        $alertas = $auth->validarlogin();
        
        if(empty($alertas)){
            //Comprobarque exista el usuario
            $usuario = Usuario::where('email', $auth->email);

            if($usuario){
                $usuario->comprobarPasswordAndVerificado($auth->password);
                if($usuario){
                    //autenticar usuario
                    session_start();

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //redireccionamiento
                    if($usuario->admin === "1"){
                        $_SESSION['admin'] = $usuario->admin ?? null;
                        header('Location: /admin');
                    }else{
                        header('Location: /cita');
                    }
                }
            }else{
                Usuario::setAlerta('error', 'Usuario no encontrado');
            }
        }
        }

            $alertas = Usuario::getAlertas();
            $router->render('auth/login',[
                'alertas'=>$alertas,
                'auth'=>$auth

            ]);
            
    }

    public static function logout(Router $router){
       session_start();

       $_SESSION = [];

       header('Location: /');
    }

    public static function olvide(Router $router){

        $alertas = [];
        if($_SERVER['REQUEST_METHOD']  === 'POST'){
            $auth = new Usuario($_POST);
            
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);
                if($usuario && $usuario->confirmado === "1"){
                    
                    //Generar token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //ENVIAR EL EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //enviar email
                    Usuario::setAlerta('exito','Revisa tu email');
                    
                   
                }else{
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas'=>$alertas
        ]);

    }

    public static function recuperar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $error = false;

        $usuario = Usuario::where('token',$token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD']  === 'POST'){

            $password = new Usuario($_POST);
            $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado =$usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }


        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas'=> $alertas,
            'error' => $error

        ]);
    }
    public static function crear(Router $router){
        $usuario = new Usuario;
        //Alertas Vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD']  === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //revisar que alertas este vacio

            if(empty($alertas)){
                //verificar que el usuario no este registrado
               $resultado = $usuario->existeUsuario();

               if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
               }else{
                //hashear el password
                $usuario->hashPassword();
                
                //crear token
                $usuario->crearToken();

                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                $email->enviarConfirmacion();

                
                $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }
               }
            }
            
        }
        $router->render('auth/crear-cuenta',[
            'usuario'=> $usuario,
            'alertas'=> $alertas
        ]);
    }


    public static function mensaje(Router $router){

        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){

        $alertas = [];

        $token= s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no Valido');
        }else{
            //Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;

            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada Correctamente');

        }
        $alertas =Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
                            'alertas'=>$alertas
                        ]);
    }
    
}