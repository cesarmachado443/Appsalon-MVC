<?php

namespace Model;

class UsuariosHobbies extends ActiveRecord{

    protected static $tabla = "usuarios_hobbies";
    protected static $columnasDB = ['id','hobbie_id','usuario_id'];

    public $id;
    public $hobbie_id;
    public $usuario_id;

    //estas son de una sub consulta
    public $nombre;
    public $descripcion;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->hobbie_id = $args['hobbie_id'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';


    }

    public static function mapearHobbiesConUsuarios($Usuario_id){
        $queryHobbies = "SELECT h.id, h.nombre, h.descripcion 
                         FROM hobbies h 
                         INNER JOIN usuarios_hobbies uh ON h.id = uh.id 
                         WHERE uh.usuario_id = {$Usuario_id}";
        $hobbies = self::consultarSQL($queryHobbies);

        $hobbiesArray = [];
        foreach ($hobbies as $hobby) {
            $hobbiesArray[] = array(
                "id" =>intval($hobby->id),
                "nombre" => $hobby->nombre,
                "descripcion" => $hobby->descripcion
            );
        }

        return $hobbiesArray  ;
    }
}