<?php
// Controlador/ExportarControlador.php
require_once __DIR__ . '/../Modelo/Paquetes.php';
require_once __DIR__ . '/../vendor/autoload.php'; // PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportarControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Paquetes();
    }

    public function exportarExcel() {
        // Recoger los filtros de la URL, si están definidos.
        $canal      = $_GET['canal'] ?? '';
        $fecha      = $_GET['fecha'] ?? '';
        $controlado = $_GET['controlado'] ?? '';

        // Preparar los filtros, solo incluir los no vacíos.
        $filtros = [];
        if ($canal)      $filtros['canal']      = $canal;
        if ($fecha)      $filtros['fecha']      = $fecha;
        if ($controlado) $filtros['controlado'] = $controlado;

        // Obtener los datos filtrados desde el modelo
        $datos = empty($filtros) ? $this->modelo->obtenerTodos() : $this->modelo->filtrarGuias($filtros);

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezado
        $sheet->fromArray(['ID','Guía','Fecha','Canal','Controlado'], null, 'A1');

        // Datos de las filas
        $row = 2;
        foreach ($datos as $r) {
            $sheet->setCellValue("A{$row}", $r['id']);
            $sheet->setCellValue("B{$row}", $r['nro_guia']);
            $sheet->setCellValue("C{$row}", $r['fecha']);
            $sheet->setCellValue("D{$row}", $r['canal']);
            $sheet->setCellValue("E{$row}", $r['controlado'] == 1 ? 'Sí' : 'No');
            $row++;
        }

        // Descargar el archivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="guias_exportadas.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

