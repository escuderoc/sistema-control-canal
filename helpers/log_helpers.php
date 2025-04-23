<?php
require_once __DIR__ . '/../Modelo/Conexion.php';

function registrarLog($usuario, $accion, $descripcion) {
    try {
        $conn = (new Conexion())->conectar();
        $stmt = $conn->prepare("INSERT INTO logs (usuario, accion, descripcion) VALUES (:usuario, :accion, :descripcion)");
        $stmt->execute([
            ':usuario' => $usuario,
            ':accion' => $accion,
            ':descripcion' => $descripcion
        ]);
    } catch (PDOException $e) {
        error_log("Error al registrar log: " . $e->getMessage());
        // No hacer echo aquí, ya que es un helper silencioso
    }
}