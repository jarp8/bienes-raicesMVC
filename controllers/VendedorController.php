<?php 

namespace Controllers;

use Exception;
use MVC\Router;
use Model\Vendedor;

class VendedorController {

    public static function crear(Router $router) {
        $vendedor = new Vendedor;

        // Arreglo con mensajes de errores
        $errores = Vendedor::getErrores();

        // Cuando se haga la petición post
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Crear una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']);
        
            // Validar que no haya campos vacíos
             $errores = $vendedor->validar();
        
             if(empty($errores)) {
                $vendedor->guardar();
             }
        }

        $router->render('vendedores/crear', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar('/admin');
        $vendedor = Vendedor::find($id);

        $errores = Vendedor::getErrores();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Asignar los valores
            $args = $_POST['vendedor'];
        
            // Sincronizar objeto en memoria
            $vendedor->sincronizar($args);
        
            // Validación
            $errores = $vendedor->validar();
        
            if(empty($errores)) {
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/actualizar', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        try {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Validar id
                $id = $_POST['id'];
                $id = filter_var($id, FILTER_VALIDATE_INT);
        
                if($id) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar(); 
                }
            }
        } catch (Exception $e) {
            header('Location: /admin?resultado=4');
        }
    }
}