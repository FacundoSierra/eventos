<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


// Iniciar sesión solo si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'Config/Config.php';

// Obtener la ruta de la URL

$ruta = isset($_GET['url']) ? $_GET['url'] : "Home/index";

// Protege todas las rutas excepto las de autenticación
if (!isset($_SESSION['usuario_id']) && !preg_match("/^Auth\//", $ruta)) {
    header("Location: " . base_url . "Auth/login");
    exit;
}

$array = explode("/", $ruta);
$controller = $array[0] ?? 'Home';
$metodo = $array[1] ?? 'index';
$parametro = isset($array[2]) ? implode(",", array_slice($array, 2)) : "";

// Cargar el autoload de clases
require_once 'Config/App/Autoload.php';

// Verificar si el controlador existe
$dirControllers = "Controllers/" . $controller . ".php";
// if (!class_exists($controller)) {
//     die("Error: El controlador <strong>$controller</strong> no existe.");
// }
// if (!method_exists($controller, $metodo)) {
//     die("Error: El método <strong>$metodo</strong> no existe en <strong>$controller</strong>.");
// }

if (file_exists($dirControllers)) {
    require_once $dirControllers;
    
    // Verificar si la clase del controlador existe
    if (class_exists($controller)) {
        $controller = new $controller();

        // Verificar si el método existe dentro del controlador
        if (method_exists($controller, $metodo)) {
            $controller->$metodo($parametro);
        } else {
            die("Error: El método <strong>$metodo</strong> no existe en el controlador <strong>$controller</strong>.");
        }
    } else {
        die("Error: La clase del controlador <strong>$controller</strong> no está definida.");
    }
} else {
    die("Error: El controlador <strong>$controller</strong> no existe.");
}

