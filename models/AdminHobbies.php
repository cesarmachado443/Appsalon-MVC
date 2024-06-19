<?php

namespace Model;

class AdminHobbies extends ActiveRecord{

    protected static $tabla = "usuarios_hobbies";
    protected static $columnasDB = ['id','hobbie_id','usuario_id'];

    public $id;
    public $hobbie_id;
    public $usuario_id;

    public function __construct($args = [])
    {
        $this->id = isset($args['id']) ? intval($args['id']) : null;
        $this->hobbie_id = $args['hobbie_id'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';


    }

    
}