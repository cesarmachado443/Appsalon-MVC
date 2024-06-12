<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\APIController;


$router = new Router();



//ecotic


$router->post('/ecotic/api/v1/usuario/auth',[APIController::class,'auth']);

//estos no funcionan revisar
$router->get('/ecotic/api/v1/hobbie',[APIController::class,'hobbies']);
$router->get('/ecotic/api/v1/usuario',[APIController::class,'usuarios']);


$router->post('/ecotic/api/v1/hobbie2',[APIController::class,'hobbies2']);
$router->post('/ecotic/api/v1/usuario2',[APIController::class,'usuarios2']);
$router->post('/ecotic/api/v1/mensaje2',[APIController::class,'mensaje2']);




// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();