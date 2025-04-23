<?php
// =========================
// ARCHIVO: Controlador/importarControlador.php
// =========================

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../Modelo/Paquetes.php');

require_once(dirname(__DIR__) . "/vendor/autoload.php");
require_once dirname(__DIR__) . '/helpers/log_helpers.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportacionControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Paquetes();
    }
    public function gestionar()
    {
        $accion = $_GET['accion'] ?? '';

        switch ($accion) {
            case 'filtrar':
                $this->filtrarGuias();
                break;
            case 'importarExcel':
                $this->importarExcel();
                break;
            default:
                http_response_code(400);
                echo json_encode(["success" => false, "mensaje" => "Acción no válida en ImportacionControlador"]);
                break;
        }
    }

    public function importarExcel()
    {
        if (isset($_FILES['archivoExcel']) && $_FILES['archivoExcel']['error'] == 0) {
            $archivoTemporal = $_FILES['archivoExcel']['tmp_name'];

            // Cargar el archivo
            $spreadsheet = IOFactory::load($archivoTemporal);
            $hoja = $spreadsheet->getActiveSheet();
            $datos = $hoja->toArray();

            // Quitar la fila de encabezado
            unset($datos[0]);
            // var_dump($datos);
            $paquetes = [];

            foreach ($datos as $fila) {
                // Saltear filas completamente vacías
                if (array_filter($fila)) {
                    $paquetes[] = [
                        'guiasmad'     => $fila[0],
                        'nro_guia'     => $fila[1],
                        'fecha'        => date('Y-m-d', strtotime($fila[2])), // Convertir a formato SQL
                        'controlado'   => $fila[3],
                        'canal'        => strtolower(trim($fila[4])),
                    ];
                }
            }


            if (count($paquetes) > 0) {
                $modelo = new Paquetes();
                $resultado = $modelo->insertarGuias($paquetes);
                //  echo "<pre>";
                // print_r($resultado);
                // echo "</pre>";
                if ($resultado['success'] != true) {
                    registrarLog($_SESSION['usuario'], 'insert guias', $resultado['mensaje']);
                    echo json_encode(['success' => false, 'mensaje' => $resultado["mensaje"]]);

                    exit;
                }
                // echo json_encode(['success' => true, 'mensaje' => "Se importaron $cantidad guias."]);
                $mensaje = $resultado['mensaje'];
                $nrosGuia = array_map(fn($p) => $p['nro_guia'], $paquetes);
                $listaNros = implode(', ', $nrosGuia);

                registrarLog($_SESSION['usuario'], 'insert guias', ' | guías: ' . $listaNros);
                // registrarLog($_SESSION['usuario'], 'insert guias', $mensaje. 'guias :'.$paquetes['nro_guia']);
                echo json_encode(['success' => true, 'mensaje' => $resultado['mensaje']]); // Esto sí responde al frontend
            } else {
                registrarLog($_SESSION['usuario'], 'insert guias', 'No se encontraron datos válidos para importar.');
                echo json_encode(['success' => false, 'mensaje' => "No se encontraron datos válidos para importar."]);
            }
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'No se pudo procesar el archivo.']);
        }
    }


    public function filtrarGuias()
    {
        header("Content-Type: application/json");

        $filtros = [
            "fecha"      => $_GET['fecha'] ?? null,
            "controlado" => $_GET['controlado'] ?? null,
            "canal"      => $_GET['canal'] ?? null
        ];

        try {
            $resultado = $this->modelo->filtrarGuias($filtros);
            echo json_encode(["success" => true, "guias" => $resultado]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "mensaje" => "Error al filtrar: " . $e->getMessage()]);
        }
    }
}
