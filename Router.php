<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];
    public array $putRoutes = [];
    public array $deletetRoutes = [];

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
        $this->deletetRoutes[$url] = $fn;
    }
    

    public function comprobarRutas()
    {
        
        session_start();


        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        
        switch($method){
            case 'GET':
                $fn = $this->getRoutes[$currentUrl] ?? null;
                break;
            case 'POST':
                $fn = $this->postRoutes[$currentUrl] ?? null;
                break;
            case 'POST':
                $fn = $this->putRoutes[$currentUrl] ?? null;
                break;
            case 'DELETE':
                $fn = $this->deletetRoutes[$currentUrl] ?? null;
                break;
            default:
                echo "metodo no permitido";
                break;
        }


        if ( $fn ) {
            // Call user fn va a llamar una función cuando no sabemos cual sera
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

}
