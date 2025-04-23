<?php

require_once(__DIR__ . "/../Modelo/Paquetes.php");
require_once dirname(__DIR__) . '/helpers/log_helpers.php';



class PaqueteControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new Paquetes();
    }
    public function gestionar() {
        $accion = $_GET['accion'] ?? '';

        switch ($accion) {
            case 'totalesGenerales':
                $this->obtenerTotalesGenerales();
                break;

            case 'totalesPorCanal':
                $this->obtenerTotalesPorCanal();
                break;

            case 'editarGuia':
                $this->editarGuia();
                break;

            case 'eliminarGuia':
                $this->eliminarGuia();
                break;

            default:
                http_response_code(400);
                echo json_encode(["estado" => "error", "mensaje" => "Acción no válida"]);
                break;
        }
    }    
    public function mostrarVista() {
        registrarLog($_SESSION['usuario'],'mostrar vista','mostrar vista');
        $totalesCanal = $this->modelo->obtenerTotalesCanal();
        $totalesGeneral = $this->modelo->obtenerTotalesGenerales();
        require(__DIR__ . "/../Vista/controlVista.php");
    }

    public function controlar() {
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nro_guia'])) {
            $nroGuia = trim($_POST['nro_guia']);

            try {
                $resultado = $this->modelo->controlarPaquete($nroGuia);
                echo json_encode($resultado);
                registrarLog($_SESSION['usuario'],'Controlar guia: '.$nroGuia,'paquete controldo');

            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    "estado" => "error",
                    "mensaje" => "Error al controlar el paquete: " . $e->getMessage()
                ]);
                registrarLog($_SESSION['usuario'],'Controlar guia: '.$nroGuia,'Error al controlar el paquete');

            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "mensaje" => "Solicitud inválida. Falta el número de guía."
            ]);
            registrarLog($_SESSION['usuario'],'Controlar guia','Solicitud inválida. Falta el número de guía');

        }
    }

    public function obtenerTotalesGenerales() {
        header("Content-Type: application/json");

        try {
            $resultado = $this->modelo->obtenerTotalesControlados();
            echo json_encode($resultado);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "mensaje" => "Error al obtener los totales generales."
            ]);
        }
    }

    public function obtenerTotalesPorCanal() {
        header("Content-Type: application/json");

        try {
            $resultado = $this->modelo->obtenerTotalesControlados();
            $resultado["success"] = true;
            echo json_encode($resultado);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "mensaje" => "Error al obtener los totales por canal."
            ]);
        }
    }
    public function eliminarGuia() {
        if (isset($_GET['nro_guia'])) {
            $nroGuia = $_GET['nro_guia'];
    
            $resultado = $this->modelo->eliminarGuia($nroGuia);
    
            if ($resultado['success']) {
                registrarLog($_SESSION['usuario'],'Eliminar guia: '.$nroGuia,'Guia eliminada correctamente');
                echo json_encode(['success' => true, 'mensaje' => 'Guía eliminada correctamente.']);

                return;
            } elseif(!$resultado['success']) {
                registrarLog($_SESSION['usuario'],'Eliminar guia: '.$nroGuia,'No se pudo eliminar la guía');
                echo json_encode(['success' => false, 'mensaje' => 'No se pudo eliminar la guía.']);

            }
        } else {
            registrarLog($_SESSION['usuario'],'Eliminar guia: ','Nro. guía no proporcionado.');
            echo json_encode(['success' => false, 'message' => 'Nro. guía no proporcionado.']);

        }
    }
    public function editarGuia(){
        $datos = json_decode(file_get_contents("php://input"), true);

    if (!isset($datos['nro_guia'])) {
        registrarLog($_SESSION['usuario'],'Eliminar guia: ','Falta el número de guía.');
        echo json_encode(["success" => false, "mensaje" => "Falta el número de guía"]);

        return;
    }

    $resultado = $this->modelo->editarGuia($datos);
    registrarLog($_SESSION['usuario'],'Update guia: '.$datos['nro_guia'],'guia editada.');
    
    echo json_encode($resultado);    
    }
}

