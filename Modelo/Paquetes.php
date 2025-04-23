<?php
// =========================
// ARCHIVO: Modelo/Paquetes.php
// =========================

require_once "Conexion.php";

class Paquetes {
    private $conn;

    public function __construct() {
        require_once(__DIR__ . '/Conexion.php');
        $this->conn = Conexion::conectar();
    }

    // Obtener totales por canal y no controlados
    public function obtenerTotalesCanal() {
        $sql = "SELECT canal, COUNT(*) AS cantidad FROM paquetes WHERE controlado = 0 OR controlado IS NULL GROUP BY canal";
    //     $sql = "
    //     SELECT
    //         canal,
    //         SUM(controlado = 0 OR controlado IS NULL) AS cantidad
    //     FROM paquetes
    //     GROUP BY canal
    // ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log(print_r($resultados, true));        
        $canales = ['verde' => 0, 'amarillo' => 0, 'rojo' => 0];
    
        foreach ($resultados as $fila) {
            $canal = strtolower($fila['canal']);
            if (array_key_exists($canal, $canales)) {
                $canales[$canal] = $fila['cantidad'];
            }
        }
        return $canales;
    }

    // Controlar un paquete por número de guía
    public function controlarPaquete($nroGuia) {
        $stmt = $this->conn->prepare("SELECT controlado, canal FROM paquetes WHERE nro_guia = :nro_guia");
        $stmt->bindParam(':nro_guia', $nroGuia);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($resultado) {
            if ($resultado['controlado'] == 1) {
                return [
                    'success' => false,
                    'estado' => 'ya_controlado',
                    'mensaje' => 'El paquete ya fue controlado previamente',
                    'nro_guia' => $nroGuia,
                    'canal' => $resultado['canal'] ?? null
                    // 'data' => [
                    //     'nro_guia' => $nroGuia,
                    //     'canal' => $resultado['canal'] ?? null
                    // ]
                ];
            } else {
                $update = $this->conn->prepare("UPDATE paquetes SET controlado = 1 WHERE nro_guia = :nro_guia");
                $update->bindParam(':nro_guia', $nroGuia);
                $update->execute();
    
                return [
                    'success' => true,
                    'estado' => 'ok',
                    'mensaje' => 'Paquete controlado exitosamente',
                    // 'data' => [
                    'nro_guia' => $nroGuia,
                    'canal' => $resultado['canal'] ?? null
                    // ]
                ];
            }
        } else {
            return [
                'success' => false,
                'estado' => 'error',
                'mensaje' => 'El paquete no fue encontrado'
            ];
        }
    }
    

    public function obtenerTotalesGenerales(){
        $totales = "SELECT COUNT(*) as total, SUM(CASE WHEN controlado = 1 THEN 1 ELSE 0 END) AS controlado FROM paquetes";
    
        $stmt = $this->conn->query($totales);
        return $stmt->fetch((PDO::FETCH_ASSOC));
    
    }
    public function obtenerTotalesControlados() {
        // $sql = "SELECT 
        //             COUNT(*) AS total,
        //             SUM(CASE WHEN controlado = 1 THEN 1 ELSE 0 END) AS controlados,
        //             SUM(CASE WHEN canal = 'Verde' AND controlado = 0 THEN 1 ELSE 0 END) AS verde,
        //             SUM(CASE WHEN canal = 'Amarillo' AND controlado = 0 THEN 1 ELSE 0 END) AS amarillo,
        //             SUM(CASE WHEN canal = 'Rojo' AND controlado = 0 THEN 1 ELSE 0 END) AS rojo
        //         FROM paquetes";
        $sql = "
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN controlado = 1 THEN 1 ELSE 0 END) AS controlados,
            SUM(CASE WHEN canal = 'Verde'  
                      AND COALESCE(controlado,0) = 0 
                 THEN 1 ELSE 0 END) AS verde,
            SUM(CASE WHEN canal = 'Amarillo' 
                      AND COALESCE(controlado,0) = 0 
                 THEN 1 ELSE 0 END) AS amarillo,
            SUM(CASE WHEN canal = 'Rojo' 
                      AND COALESCE(controlado,0) = 0 
                 THEN 1 ELSE 0 END) AS rojo
        FROM paquetes
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertarGuias($guias) {
        try {
            $duplicados = [];
            $validos = [];
    
            // Detectar duplicados
            foreach ($guias as $guia) {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM paquetes WHERE nro_guia = :nro_guia");
                $stmt->bindParam(':nro_guia', $guia['nro_guia']);
                $stmt->execute();
    
                if ($stmt->fetchColumn() > 0) {
                    $duplicados[] = $guia['nro_guia'];
                } else {
                    $validos[] = $guia;
                }
            }
    
            if (empty($validos)) {
                return [
                    'success' => false,
                    'mensaje' => 'No se importaron guías. Todas ya existen en la base de datos.',
                    'cantidad' => 0,
                    'duplicados' => $duplicados
                ];
            }
    
            // Insertar válidos
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("INSERT INTO paquetes (guiasmad, nro_guia, fecha, controlado, canal) 
                                          VALUES (:guiasmad, :nro_guia, :fecha, :controlado, :canal)");
    
            foreach ($validos as $guia) {
                $stmt->bindParam(':guiasmad', $guia['guiasmad']);
                $stmt->bindParam(':nro_guia', $guia['nro_guia']);
                $stmt->bindParam(':fecha', $guia['fecha']);
                $stmt->bindParam(':controlado', $guia['controlado']);
                $stmt->bindParam(':canal', $guia['canal']);
                $stmt->execute();
            }
    
            $this->conn->commit();
    
            return [
                'success' => true,
                'mensaje' => 'Se importaron correctamente ' . count($validos) . ' guías.',
                'cantidad' => count($validos),
                'duplicados' => $duplicados
            ];
    
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'mensaje' => 'Error en la importación: ' . $e->getMessage(),
                'cantidad' => 0
            ];
        }
    }
    
    public function filtrarGuias($filtros) {
        $sql = "SELECT * FROM paquetes WHERE 1=1";
        $params = [];
    
        if (!empty($filtros['fecha'])) {
            $sql .= " AND fecha = :fecha";
            $params[':fecha'] = $filtros['fecha'];
        }
    
        if (isset($filtros['controlado']) && $filtros['controlado'] !== '') {
            $sql .= " AND controlado = :controlado";
            $params[':controlado'] = $filtros['controlado'];
        }
    
        if (!empty($filtros['canal'])) {
            $sql .= " AND canal = :canal";
            $params[':canal'] = $filtros['canal'];
        }
    
        $sql .= " ORDER BY fecha DESC";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al filtrar guías: " . $e->getMessage());
            return [];
        }
    }
    public function eliminarGuia($guia) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM paquetes WHERE nro_guia = :nro_guia");
            $stmt->bindParam(':nro_guia', $guia, PDO::PARAM_INT);
            $stmt->execute();
    
            return ['success' => true, 'mensaje' => 'Guia eliminada correctamente.'];
        } catch (PDOException $e) {
            return ['success' => false, 'mensaje' => 'Error al eliminar la guia: ' . $e->getMessage()];
        }
    }
    public function editarGuia($datos) {
        try {
            $sql = "UPDATE paquetes SET fecha = :fecha, canal = :canal, controlado = :controlado WHERE nro_guia = :nro_guia";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":fecha"      => $datos["fecha"],
                ":canal"      => $datos["canal"],
                ":controlado" => $datos["controlado"],
                ":nro_guia"   => $datos["nro_guia"]
            ]);
    
            return [
                "success" => true,
                "mensaje" => "Guía actualizada correctamente"
            ];
        } catch (PDOException $e) {
            return [
                "success" => false,
                "mensaje" => "Error al actualizar: " . $e->getMessage()
            ];
        }
    }
    
    
    
    // Guardar log
    // public function guardarLog($nroGuia, $canal, $observacion): void {
    //     $date = mktime("d-n-y h:i:sa");
    //     $stmt = $this->conn->prepare("INSERT INTO logs (nro_guia, canal, accion, fecha, usuario) VALUES (?, ?, ?)");
    //     // $stmt->execute([$nroGuia, $canal, $observacion]);
    //     $stmt->execute([
    //         ':nro_guia' => $nroGuia,
    //         ':canal' => $canal,
    //         ':accion' => $observacion,
    //         ':fecha' => $date,
    //         ':usuario' => 'test'
    //     ]);
    // }
}