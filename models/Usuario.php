<?php
namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombres', 'apellidos','correo','password'];


    public $id;
    public $nombres;
    public $apellidos;
    public $correo;
    public $password;
    

    public function __construct($args = []){
        $this->id =$args['id'] ?? null;
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

    public function crearToken(){
        $this->token = uniqid();
        
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