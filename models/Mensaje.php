<?php
namespace Model;

class Mensaje extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'mensajes';
    protected static $columnasDB = ['id', 'titulo', 'descripcion','image'];


    public int $id;
    public $titulo;
    public $descripcion;
    public $image;
    

    public function __construct($args = []){
        $this->id = intval($args['id']) ?? null;
        $this->titulo =$args['titulo'] ?? '';
        $this->descripcion =$args['descripcion'] ?? '';
        $this->image =$args['image'] ?? '';
    }

}