<?php
namespace Controllers;

use Model\Cita;
use Model\Hobbies;
use Model\Mensaje;
use Model\Usuario;
use Firebase\JWT\JWT;
use Model\AdminHobbies;
use Model\UsuariosHobbies;
use Model\UsuariosInformacion;


class APIController {

    

    public static function auth() {

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
                            $Usuario_id = $usuario->id;
                            $queryHobbies = "SELECT h.id, h.nombre, h.descripcion 
                                                FROM usuarios_hobbies uh 
                                                INNER JOIN hobbies h ON h.id = uh.hobbie_id 
                                                WHERE uh.usuario_id = {$Usuario_id}";
                            $hobbies = UsuariosHobbies::SQL($queryHobbies);
                            
                            $hobbiesArray = [];
                            foreach ($hobbies as $hobby) {
                                $hobbiesArray[] = array(
                                    "id" =>intval($hobby->id),
                                    "nombre" => $hobby->nombre,
                                    "descripcion" => $hobby->descripcion
                                );
                            }
                            
                            
                            $usuario_info = UsuariosInformacion::where('usuario_id',intval($usuario->id));
                        
                            //crear jwt
                            $token = Usuario::jwt($usuario->id, $usuario->correo);
                            $jwt = JWT::encode($token,"ghsjldhlajkhfkjadfjkdsf","HS256");

                            
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
                                        "correo" => $usuario->correo,
                                        "hobbies" => $hobbiesArray,
                                        "expectativas"=>$usuario_info->expectativas,
                                        "avatar"=>$usuario_info->avatar,
                                        "descripcion"=>$usuario_info->descripcion_personal 

                                    ),
                                "token"=>$jwt
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
        

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            
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

    public static function usuarios() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // traer todos los usuarios
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

    public static function mensajes() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

    public static function restarHobie(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            session_start();
    
            // Leer el contenido de la solicitud PUT
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
    
            // Verificar si los datos fueron decodificados correctamente
            if (json_last_error() !== JSON_ERROR_NONE) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Datos de entrada inválidos'
                ];
                echo json_encode($respuesta);
                return;
            }

            //eliminamos los hobies 
            foreach($data['deleteHobbies'] as $hobie){
                $id = $hobie['id'];
                $usuario_hobbie = AdminHobbies::find($id);
                if(!empty($usuario_hobbie)){
                    $usuario_hobbie->eliminar();
                }
            }

            //Guardamos los nuevos
            $Todos_los_hobies = AdminHobbies::belongsTo('usuario_id',$data['usuarioId']);
            foreach($data['hobbies'] as $hobie){
                $nuevo_hobbie_id = intval($hobie['id']);  // Convertir a entero
                $existe = false;

                    // Verificar si ya existe el hobby en el arreglo $Todos_los_hobies
                    foreach($Todos_los_hobies as $existingHobbie) {
                        if (intval($existingHobbie->hobbie_id) === $nuevo_hobbie_id) {
                            $existe = true;
                            break;
                        }
                    }
                        if (!$existe) {
                        $usuario_hobbie = new AdminHobbies;
                        $usuario_hobbie->hobbie_id = $hobie['id'];
                        $usuario_hobbie->usuario_id = $data['usuarioId'];
                        $resultado = $usuario_hobbie->guardar();  
                        }
                        
            }
            
            
            


        }
    }
    
    public static function actualizar_usuario(){
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            session_start();
    
            // Leer el contenido de la solicitud PUT
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
    
            // Verificar si los datos fueron decodificados correctamente
            if (json_last_error() !== JSON_ERROR_NONE) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Datos de entrada inválidos'
                ];
                echo json_encode($respuesta);
                return;
            }

             //eliminamos los hobies 
             foreach($data['deleteHobbies'] as $hobie){
                $id = $hobie['id'];
                $usuario_id = $data['id'];
                $usuario_hobbie = AdminHobbies::where('hobbie_id',$id ,'usuario_id',$usuario_id);
                if(!empty($usuario_hobbie)){
                    $usuario_hobbie->eliminar();
                }
            }

            //Guardamos los nuevos
            $Todos_los_hobies = AdminHobbies::belongsTo('usuario_id',$data['id']);
            foreach($data['hobbies'] as $hobie){
                $nuevo_hobbie_id = intval($hobie['id']);  // Convertir a entero
                $existe = false;

                    // Verificar si ya existe el hobby en el arreglo $Todos_los_hobies
                    foreach($Todos_los_hobies as $existingHobbie) {
                        if (intval($existingHobbie->hobbie_id) === $nuevo_hobbie_id) {
                            $existe = true;
                            break;
                        }
                    }
                        if (!$existe) {
                        $usuario_hobbie = new AdminHobbies;
                        $usuario_hobbie->hobbie_id = $hobie['id'];
                        $usuario_hobbie->usuario_id = $data['id'];
                        $resultado = $usuario_hobbie->guardar();  
                        }
                        
            }


            //Actualizar la info del usuario
            $usuario_info = UsuariosInformacion::where('usuario_id',$data['id']);
            $usuario_info->sincronizar($data);
            if($data['descripcion']){
            $usuario_info->descripcion_personal = $data['descripcion'];
            }
            $resultado = $usuario_info->guardar();



             if($resultado){

                

                $usuario = Usuario::where('id',$data['id']);

                
                //consultar los hobbis del usuario
                            $Usuario_id = intval($usuario->id);
                            
                            $queryHobbies = "SELECT h.id, h.nombre, h.descripcion 
                                                FROM usuarios_hobbies uh 
                                                INNER JOIN hobbies h ON h.id = uh.hobbie_id 
                                                WHERE uh.usuario_id = {$Usuario_id}";

                            $hobbies = UsuariosHobbies::SQL($queryHobbies);
                            
                            $hobbiesArray = [];
                            foreach ($hobbies as $hobby) {
                                $hobbiesArray[] = array(
                                    "id" =>intval($hobby->id),
                                    "nombre" => $hobby->nombre,
                                    "descripcion" => $hobby->descripcion
                                );
                                
                            }
                            
                            
                            $usuario_info = UsuariosInformacion::where('usuario_id',intval($usuario->id));
                        
                            //crear jwt
                            $token = Usuario::jwt($usuario->id, $usuario->correo);
                            $jwt = JWT::encode($token,"ghsjldhlajkhfkjadfjkdsf","HS256");

                            
                            // Construir la respuesta con los datos del usuario y sus hobbies
                            $resultado = array(
                                "localDateTime" => date('Y-m-d\TH:i:s.u'),
                                "status" => "success",
                                "message" => null,
                                "data" => array(
                                    "usuario" => array(
                                        "id" => intval( $usuario->id),
                                        "nombres" => $usuario->nombres,
                                        "apellidos" => $usuario->apellidos,
                                        "correo" => $usuario->correo,
                                        "hobbies" => $hobbiesArray,
                                        "expectativas"=>$usuario_info->expectativas,
                                        "avatar"=>$usuario_info->avatar,
                                        "descripcion"=>$usuario_info->descripcion_personal 

                                    ),
                                "token"=>$jwt
                                )
                            );
        

                            // Si deseas responder con el JSON decodificado
                            echo json_encode($resultado);






             }

            





        }
    }

}