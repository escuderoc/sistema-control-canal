<?php
require_once "Modelo/UsuariosModelo.php";
require_once dirname(__DIR__) . '/helpers/log_helpers.php';

class LoginControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new UsuarioModelo();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];

            // Verificar si el usuario existe
            $usuarioExistente = $this->modelo->obtenerUsuarioPorNombre($usuario);
            
            if ($usuarioExistente && password_verify($password, $usuarioExistente['password'])) {
                // Login exitoso, guardar la sesión
                // session_start();
                $_SESSION['user_id']   = $usuarioExistente['id'];
                $_SESSION['usuario'] = $usuarioExistente['usuario'];
                $_SESSION['user_role'] = $usuarioExistente['rol'];
                
                registrarLog( $_SESSION['usuario'],'Login','Usuario logeado');
                header('Location: index.php');
                exit;
            } else {
                // Si las credenciales son incorrectas
                registrarLog($_SESSION['usuario'],'Login','Usuario o contraseña incorrectos');
                $_SESSION['login_error'] = "Usuario o contraseña incorrectos";
                header('Location: index.php?vista=login');
                exit;
            }
        }
    }

    public function logout()
    {
        // session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?vista=login');
        exit;
    }
}
