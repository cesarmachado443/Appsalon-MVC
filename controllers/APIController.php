<?php
namespace Controllers;

use Model\Cita;
use Model\Hobbies;
use Model\Mensaje;
use Model\Usuario;
use Model\UsuariosHobbies;
use Model\UsuariosInformacion;


class APIController {

    private static function configureCors() {
        // Permitir solicitudes desde cualquier origen
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type");
        // Permitir los métodos POST, GET, OPTIONS y encabezados requeridos
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Content-Type: application/json");
    }

    public static function auth() {
            self::configureCors();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener el JSON del cuerpo de la solicitud
            $json = file_get_contents('php://input');
    
            // Decodificar el JSON en un array asociativo
            $data = json_decode($json, true);

            // Verificar si el JSON fue decodificado correctamente
            if ($data === null) {
                // Error al decodificar el JSON
                echo "Error al decodificar el JSON";
            } else {

            $email = $data['email'];
            $password = $data['password'];

            $usuario = Usuario::where('correo',$email);


                if($usuario){
                   $password_correct = $usuario->comprobarPasswordAndVerificado($password);
                    
                    if($password_correct){
                            //consultar los hobbis del usuario
                            $hobbies = UsuariosHobbies::mapearHobbiesConUsuarios(intval($usuario->id));
                            $usuario_info = UsuariosInformacion::where('usuario_id',intval($usuario->id));
                        

                            // Construir la respuesta con los datos del usuario y sus hobbies
                            $resultado = array(
                                "localDateTime" => date('Y-m-d\TH:i:s.u'),
                                "status" => "success",
                                "message" => "Authentication successful",
                                "data" => array(
                                    "user" => array(
                                        "id" => intval( $usuario->id),
                                        "nombres" => $usuario->nombres,
                                        "apellidos" => $usuario->apellidos,
                                        "correo" => $usuario->email,
                                        "hobbies" => $hobbies,
                                        "expectativas"=>$usuario_info->expectativas,
                                        "avatar"=>$usuario_info->avatar,
                                        "descripcion"=>$usuario_info->descripcion_personal 

                                    )
                                
                                )
                            );
        

                            // Si deseas responder con el JSON decodificado
                            echo json_encode($resultado);
                    }else{
                            // Construir el arreglo asociativo para la respuesta de error
                        $resultado = array(
                            "localDateTime" => date('c'),
                            "status" => "error",
                            "message" => "Usuario o contaseña incorrecta",
                            "data" => array()
                        );

                        // Responder con el JSON de error
                        echo json_encode($resultado);
                    }
                    
                    

                }else{
                    // Construir el arreglo asociativo para la respuesta de error
                $resultado = array(
                    "localDateTime" => date('c'),
                    "status" => "error",
                    "message" => "El usuario con el email". $email ." no existe. Por favor registrese si aun no lo ha hecho!",
                    "data" => array()
                );

                // Responder con el JSON de error
                echo json_encode($resultado);
               }
            }
        }
    }

    public static function hobbies() {

        
        self::configureCors();
        // traer todos los hobbies
        $hobbies = Hobbies::all();
    
        // Construir la respuesta con los datos de los hobbies
        $resultado = array(
            "localDateTime" => date('Y-m-d\TH:i:s.u'),
            "status" => "success",
            "message" => null,
            "data" => array(
                "hobbie" => $hobbies
            )
        );
    
        echo json_encode($resultado);
    }

    public static function usuarios() {
        self::configureCors();
        // traer todos los hobbies
        $usuarios = usuario::all();
        foreach ($usuarios as &$usuario) {
            unset($usuario->password);
        }
        // Construir la respuesta con los datos de los hobbies
        $resultado = array(
            "localDateTime" => date('Y-m-d\TH:i:s.u'),
            "status" => "success",
            "message" => null,
            "data" => array(
                "usuario" => $usuarios
            )
        );
    
        echo json_encode($resultado);
    }
    
    
    public static function index(){
        $servicios = Servicio::all();

        echo json_encode($servicios);
    }

    public static function guardar(){
        //Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        //Almacena llso servicios con el id de la cita
        $idServicios = explode(",",$_POST['servicios']);
        foreach($idServicios as $idservicio){
            $args = [
                'citaId'=> $id,
                'servicioId'=>$idservicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        
       //retornamos una respuesta
        echo json_encode(['resultado'=> $resultado]);
    }

    public static function eliminar(){
        
        if($_SERVER['REQUEST_METHOD']  === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:'.$_SERVER['HTTP_REFERER']);
        }
    }


    public static function hobbies2() {
        self::configureCors();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // traer todos los hobbies
        $hobbies = Hobbies::all();
    
        // Construir la respuesta con los datos de los hobbies
        $resultado = array(
            "localDateTime" => date('Y-m-d\TH:i:s.u'),
            "status" => "success",
            "message" => null,
            "data" => array(
                "hobbie" => $hobbies
            )
        );
    
        echo json_encode($resultado);
    

           
           }
    
    }









    public static function usuarios2() {
        self::configureCors();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // traer todos los hobbies
            $usuarios = usuario::all();

            foreach ($usuarios as &$usuario) {
                // Eliminar el campo de contraseña
                unset($usuario->password);
    
                // Obtener la información del avatar del usuario
                $info_usuario = UsuariosInformacion::where('usuario_id', $usuario->id);
    
                // Si se encontró información del usuario, agregar el avatar al usuario
                if ($info_usuario) {
                    $usuario->avatar = $info_usuario->avatar;
                }
            }
            
            // Construir la respuesta con los datos de los hobbies
            $resultado = array(
                "localDateTime" => date('Y-m-d\TH:i:s.u'),
                "status" => "success",
                "message" => null,
                "data" => array(
                    "usuario" => $usuarios,
                    
                )
            );
        
            echo json_encode($resultado);
        }
    }






    public static function mensaje2() {
        self::configureCors();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // traer todos los hobbies
            $mensaje = Mensaje::all();
            
            // Construir la respuesta con los datos de los hobbies
            $resultado = array(
                "localDateTime" => date('Y-m-d\TH:i:s.u'),
                "status" => "success",
                "message" => null,
                "data" => array(
                    "mensaje" => $mensaje
                )
            );
        
            echo json_encode($resultado);
        }
    }
    


}