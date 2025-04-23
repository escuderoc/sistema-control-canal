<?php
require_once "Modelo/UsuariosModelo.php";
require_once(__DIR__ . '/../helpers/log_helpers.php');

class UsuarioControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new UsuarioModelo();
    }

    public function gestionar()
    {
        $accion = $_GET['accion'] ?? '';

        switch ($accion) {
            case 'listarUsuarios':
                $this->listar();
                break;
            case 'crearUsuario':
                $this->crear();
                break;
            case 'editarUsuario':
                $this->editar();
                break;
            case 'eliminarUsuario':
                $this->eliminar();
                break;
            default:
                echo json_encode(["success" => false, "mensaje" => "Acción de usuario no válida"]);
                break;
        }
    }

    private function listar()
    {
        $usuarios = $this->modelo->obtenerUsuarios();
        echo json_encode(["success" => true, "usuarios" => $usuarios]);
    }

    private function crear()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $resultado = $this->modelo->crearUsuario($data);
        $usuario = $_SESSION['usuario'] ?? 'desconocido';
        registrarLog($_SESSION['usuario'],'create usuario','se creo un usuario '.$data['nombre']);
        echo json_encode($resultado);
    }

    private function editar()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $resultado = $this->modelo->editarUsuario($data);
        // $usuario = $_SESSION['usuario'] ?? 'desconocido';
        registrarLog($_SESSION['usuario'],'update usuario','se edito el usuario '.$data['nombre']);
        echo json_encode($resultado);
    }

    private function eliminar()
    {
        $id = $_GET['id'] ?? null;
        $resultado = $this->modelo->eliminarUsuario($id);
        // $usuario = $_SESSION['usuario'] ?? 'desconocido';
        registrarLog($_SESSION['usuario'],'delete usuario','se elimini el usuario_id '.$id);

        echo json_encode($resultado);
    }
}
