<?php
namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido','email','telefono','password','admin','confirmado','token'];


    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $password;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id =$args['id'] ?? null;
        $this->nombre =$args['nombre'] ?? '';
        $this->apellido =$args['apellido'] ?? '';
        $this->email =$args['email'] ?? '';
        $this->telefono =$args['telefono'] ?? '';
        $this->password =$args['password'] ?? '';
        $this->admin =$args['admin'] ?? '0';
        $this->confirmado =$args['confirmado'] ?? '0';
        $this->token =$args['token'] ?? '';

    }


    //Mensajes de validacion para la creación de una cuenta 
    public function validarNuevaCuenta(){

        //primero nos traemos los que esta en objeto con el $this
        $props = get_object_vars($this);
        //iteramos sobre el objeto que creamos para vers si alguno de los campos esta vacio 
        foreach ($props as $prop => $value) {
            
            //con el if definimos que variables no queremos que compare, esto para que no nos devuelva la alerta de ejemplo id 
            if($prop === 'id') continue;
            if($prop === 'admin') continue;
            if($prop === 'confirmado') continue;
            if($prop === 'token') continue;

            if (empty($value) ) {
                //alerta se hereda de activeRecord
                self::$alertas['error'][] = "El campo <span>'$prop'</span> es obligatorio.";
            }
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El campo <span> password </span>  debe contener al menos <span>6</span> caracteres";
        }
        return self::$alertas;
    }

    //Revisa si el usuario existe
    public function existeUsuario(){
        $query = "SELECT * FROM " .self::$tabla." WHERE email = '". $this->email . "' LIMIT 1";

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
    

    public function validarlogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El <span>email</span> es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El <span>password</span>  es obligatorio';
        }

        return self::$alertas;
    }

    public function comprobarPasswordAndVerificado($password){
        
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Password o confirmacion falta';
        }else{
            return true;
        }
        
    }
    public function validarPassword(){
        if (empty($value) ) {
            //alerta se hereda de activeRecord
            self::$alertas['error'][] = "El <span>password</span> es obligatorio.";
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El campo <span> password </span>  debe contener al menos <span>6</span> caracteres";
        }
        return self::$alertas;
    }

    function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El <span>email</span> es obligatorio';
        }
        return self::$alertas;
    }
}