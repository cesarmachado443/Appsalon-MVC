<?php

namespace Controllers;

use MVC\Router;
use Model\AdminCita;

class AdminController {
    public static function index(Router $router){

        session_start();

        isAdmin();
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-',$fecha);

        if(!checkdate( intval($fechas[1]), intval($fechas[2]), intval($fechas[0]))){
            header('Location: /404');
        }

        

        //consultar la base de datos
        $consulta = "SELECT
                             c.id,
                             c.hora,
                             CONCAT(u.nombre,' ',u.apellido) as cliente,
                             u.email,
                             u.telefono,
                             sv.nombre as servicio,
                             sv.precio";
        
        $consulta .= " FROM citas as c";
        $consulta .= " inner JOIN usuarios as u";
        $consulta .= " on c.usuarioId= u.id ";
        $consulta .= " inner Join citasservicios as cs";
        $consulta .= " on cs.citaId=c.id ";
        $consulta .= " inner join servicios as sv ";
        $consulta .= " on sv.id=cs.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);

        
        $router->render('admin/index',[
            'nombre' => $_SESSION['nombre'],
            'citas'=>$citas,
            'fecha'=>$fecha
        ]);
    }
}