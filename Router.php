<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];
    public array $putRoutes = [];
    public array $deleteRoutes = []; // Corregido de deletetRoutes a deleteRoutes

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function put($url, $fn)
    {
        $this->putRoutes[$url] = $fn;
    }

    public function delete($url, $fn)
    {
        $this->deleteRoutes[$url] = $fn;
    }

    public function comprobarRutas()
    {
        session_start();

        // Configuración de las cabeceras CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: *");
        header("Content-Type: application/json");
        
        // Manejo de la solicitud preflight (OPTIONS)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Max-Age: 86400"); // Cache preflight request por 1 día
            header("Content-Length: 0");
            header("Content-Type: text/plain");
            exit;
        }

        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        switch($method){
            case 'GET':
                $fn = $this->getRoutes[$currentUrl] ?? null;
                break;
            case 'POST':
                $fn = $this->postRoutes[$currentUrl] ?? null;
                break;
            case 'PUT':
                $fn = $this->putRoutes[$currentUrl] ?? null;
                break;
            case 'DELETE':
                $fn = $this->deleteRoutes[$currentUrl] ?? null;
                break;
            default:
                echo "Método no permitido";
                return; // Asegúrate de terminar la ejecución si el método no es permitido
        }

        if ($fn) {
            // Llamar a la función de usuario
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }
}
