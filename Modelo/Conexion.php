<?php
class Conexion {
    private static $host = "localhost";
    private static $user = "root";
    private static $password = "";
    private static $db = "bbdd_control_ezeiza";
    private static $conexion;

    public static function conectar() {
        if (!isset(self::$conexion)) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=utf8";
                self::$conexion = new PDO($dsn, self::$user, self::$password);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }
}
