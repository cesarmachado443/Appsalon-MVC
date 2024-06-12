<?php

namespace Model;

class UsuariosInformacion extends ActiveRecord{

    protected static $tabla = "usuarios_informacion";
    protected static $columnasDB = ['id','descripcion_personal','expectativas','avatar','usuario_id'];

    public $id;
    public $descripcion_personal;
    public $expectativas;
    public $avatar;
    public $usuario_id;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->descripcion_personal = $args['descripcion_personal'] ?? '';
        $this->expectativas = $args['expectativas'] ?? '';
        $this->avatar = $args['avatar'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? null;

    }

    
}