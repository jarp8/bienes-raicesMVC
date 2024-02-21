<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas() {
        session_start();

        // Arreglo de rutas protegidas
        $rutas_protegidas = [
            '/admin',
            '/propiedades/crear',
            '/propiedades/actualizar',
            '/propiedades/eliminar',
            '/vendodores/crear',
            '/vendodores/actualizar',
            '/vendodores/eliminar'
        ];

        $auth = $_SESSION['login'] ?? null;

        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if($metodo == 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // Proteger las rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth) {
            header('Location: /');
        }

        if($fn) {
            call_user_func($fn, $this);
        } else {
            echo 'Página no encontrada';
        }
    }

    // Muestra una vista
    public function render($view , $datos = []) {

        // Variable de variable
        // Crea una variable con el nombre de la llave/key del arreglo 
        foreach($datos as $key => $value) {
            $$key = $value;
        }

        ob_start(); // Almacenamiento en memoria durante un momento
        include __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Almacena el contenido hasta aquí y limpia el buffer

        include __DIR__ . "/views/layout.php";
    }
}