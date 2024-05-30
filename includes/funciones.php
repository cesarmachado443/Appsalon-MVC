<?php

function debug($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Funcion logeueado

function isAuth(): void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

//funtcio es admin
function isAdmin():void{
    if(!isset($_SESSION['admin'])){
        header('Location:/');
    }
}


function esUltimo($actual, $proximo): bool{
    if($actual !== $proximo){
        return true;
    }
    return false;
}