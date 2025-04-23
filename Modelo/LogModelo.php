<?php

class LogModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function registrarLog($accion, $descripcion, $usuario) {
        $sql = "INSERT INTO logs (accion, descripcion, usuario) VALUES (:accion, :descripcion, :usuario)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':accion', $accion);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':usuario', $usuario);
        return $stmt->execute();
    }
}
