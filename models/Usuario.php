<?php
namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombres', 'apellidos','correo','password'];


    public int $id;
    public $nombres;
    public $apellidos;
    public $correo;
    public $password;
    

    public function __construct($args = []){
        $this->id = intval($args['id']) ?? null;
        $this->nombres =$args['nombres'] ?? '';
        $this->apellidos =$args['apellidos'] ?? '';
        $this->correo =$args['correo'] ?? '';
        $this->password =$args['password'] ?? '';
    }
    

    //Revisa si el usuario existe
    public function existeUsuario(){
        $query = "SELECT * FROM " .self::$tabla." WHERE correo = '". $this->correo . "' LIMIT 1";

        $resultado= self::$db->query($query);
        
        if($resultado->num_rows){
            self::$alertas['error'][] = "El usuario ya esta registrado";

            
        }
        return $resultado;
    }
    
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public static function jwt($id, $email){
        $time = time();

        $token = array(
            "iat" => $time,//tiempo en que incia el token
            "exp"=> $time + (60*60*24),
            "data"=>[
                "id"=> intval($id) ,
                "email"=> $email
            ]
            );
        
        return $token;
    }
    

    public function comprobarPasswordAndVerificado($password){
        
        $resultado = password_verify($password, $this->password);

        if(!$resultado){
            return false;
        }else{
            return true;
        }
        
    }

}