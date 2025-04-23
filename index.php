<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1) Si el usuario NO ha iniciado sesión y está intentando acceder a algo que no sea login, lo mando al login
if (!isset($_SESSION['user_id']) && ($_GET['accion'] ?? '') !== 'login' && ($_GET['vista'] ?? '') !== 'login') {
    header('Location: index.php?vista=login'); // Redirige al login
    exit;
}

// 2) Si el usuario YA ha iniciado sesión y está intentando acceder a la página de login, lo redirijo al home
if (isset($_SESSION['user_id']) && ($_GET['accion'] ?? '') === 'login') {
    header('Location: index.php'); // Redirige al inicio
    exit;
}
// 3) Router centralizado
if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'login':
            require_once "Controlador/LoginControlador.php";
            $controlador = new LoginControlador();
            $controlador->login(); // Realiza el login
            break;
        case 'logout':
            require_once "Controlador/LoginControlador.php";
            $controlador = new LoginControlador();
            $controlador->logout(); // Realiza el logout
            break;
        case 'importarExcel':
        case 'filtrar':
            require_once "Controlador/importarControlador.php";
            $controlador = new ImportacionControlador();
            $controlador->gestionar(); // Una función que decide qué hacer según la acción
            break;
        case 'controlar':
            require_once "Controlador/PaqueteControlador.php";
            $controlador = new PaqueteControlador();
            $controlador->controlar();
            break;    
        case 'totalesGenerales':
        case 'totalesPorCanal':
        case 'editarGuia':
        case 'eliminarGuia':
            require_once "Controlador/PaqueteControlador.php";
            $controlador = new PaqueteControlador();
            $controlador->gestionar(); // Centralizás también las acciones del otro controlador
            break;        
        case 'listarUsuarios':
            require_once "Controlador/UsuariosControlador.php";
            $controlador = new UsuarioControlador();
            $controlador-> gestionar();
            break;
        case 'crearUsuario':
        case 'editarUsuario':
        case 'eliminarUsuario':
            require_once "Controlador/UsuariosControlador.php";
            $controlador = new UsuarioControlador();
            $controlador->gestionar();
            break;  
        default:
            http_response_code(400);
            echo json_encode(["estado" => "error", "mensaje" => "Acción no válida"]);
            break;      
    }
    exit;
}

// 4) Si no hay acción, mostrar vistas
if (isset($_GET['vista'])) {
    $vista = $_GET['vista'];
    $archivoVista = __DIR__ . "/Vista/{$vista}.php";

    // ❗❗ PROTECCIÓN DE VISTA USUARIOS ❗❗
    if ($vista === 'usuarios' && $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    if (file_exists($archivoVista)) {
        include $archivoVista;
    } else {
        echo "La vista '{$vista}' no existe.";
    }
} else {
    // Vista por defecto
    include __DIR__ . "/Vista/controlVista.php";
}