<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {
    
    public static function index(Router $router) {
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $resultado = null;

        // Mostrar mensaje condicional
        $resultado = $_GET['resultado'] ?? null;

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'vendedores' => $vendedores,
            'resultado' => $resultado,
        ]);
    }

    public static function crear(Router $router) {
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        
        // Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();

        // Ejecutar el código después de que el usuario envia
        // el formulario
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Crea una nueva instancia
            $propiedad = new Propiedad($_POST['propiedad']);

            // Asignar files hacia una variable
            $imagen = $_FILES['propiedad']['name']['imagen'];

            // * Subida de archivos *
            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . $imagen;

            // Setear la imagen
            // Realiza un resize a la imagen con intervention
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            // Validar
            $errores = $propiedad->validar();

            // Revisar que el arreglo de errores este vacío
            if(empty($errores)) {
                // Crear la carpeta para subir imagenes
                if(!is_dir(CARPETAS_IMAGENES)) {
                    mkdir(CARPETAS_IMAGENES);
                }

                // Guarda la imagen en el servidor
                $image->save(CARPETAS_IMAGENES . $nombreImagen);

                // Guardar en la base de datos
                $propiedad->guardar();
            }
        }

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar('/admin');
        $propiedad = Propiedad::find($id);

        $vendedores = Vendedor::all();

        $errores = Propiedad::getErrores();

        // Ejecutar el código después de que el usuario envia
        // el formulario
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Asignar los atributos
            $args = $_POST['propiedad'];

            $propiedad->sincronizar($args);

            // Valiadación
            $errores = $propiedad->validar();

            // Asignar files hacia una variable
            $imagen = $_FILES['propiedad']['name']['imagen'];

            // Subida de arachivos
            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . $imagen;

            // Setear la imagen
            // Realiza un resize a la imagen con intervention
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }
            
            // Revisar que el arreglo de errores este vacío
            if(empty($errores)) {
                if($_FILES['propiedad']['tmp_name']['imagen']) {
                    // Almancear la imagen
                    $image->save(CARPETAS_IMAGENES . $nombreImagen);
                }
                // Actualizar
                $propiedad->guardar();
            }
        }

        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
    
            if($id) {
                $tipo = $_POST['tipo'];
                if(validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar(); 
                }
            }
        }
    }
}