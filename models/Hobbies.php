<?php

namespace Model;

class Hobbies extends ActiveRecord{

    protected static $tabla = "hobbies";
    protected static $columnasDB = ['id','nombre','descripcion'];

    public int $id;
    public $nombre;
    public $descripcion;


    public function __construct($args = [])
    {
        $this->id = intval($args['id'])?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';

    }
    



}