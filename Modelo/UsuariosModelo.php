<?php
require_once "Conexion.php"; // Ajustar si tu conexión está en otro archivo

class UsuarioModelo
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }
    public function obtenerUsuarioPorNombre($usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarios()
    {
        $stmt = $this->db->query("SELECT id, nombre, usuario, rol FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearUsuario($data)
    {
        try {
            $sql = "INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->execute([$data['nombre'], $data['usuario'], $hashed, $data['rol']]);

            return ["success" => true, "mensaje" => "Usuario creado exitosamente"];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error al crear usuario: " . $e->getMessage()];
        }
    }

    public function editarUsuario($data)
    {
        try {
            $campos = "nombre = ?, usuario = ?, rol = ?";
            $params = [$data['nombre'], $data['usuario'], $data['rol']];

            if (!empty($data['password'])) {
                $campos .= ", password = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $params[] = $data['id'];

            $sql = "UPDATE usuarios SET $campos WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return ["success" => true, "mensaje" => "Usuario actualizado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error al actualizar usuario: " . $e->getMessage()];
        }
    }

    public function eliminarUsuario($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);

            return ["success" => true, "mensaje" => "Usuario eliminado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "mensaje" => "Error al eliminar usuario: " . $e->getMessage()];
        }
    }
}
