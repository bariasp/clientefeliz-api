<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/Database.php';

class Usuario {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("SELECT * FROM usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(array $data): array {
        try {
            $sql = "INSERT INTO usuario (
                nombre, apellido, email, contrasena, fecha_nacimiento,
                telefono, direccion, rol, fecha_registro, estado
            ) VALUES (
                :nombre, :apellido, :email, :contrasena, :fecha_nacimiento,
                :telefono, :direccion, :rol, :fecha_registro, :estado
            )";

            $stmt = $this->pdo->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();

            return ['mensaje' => 'Usuario creado correctamente'];
        } catch (PDOException $e) {
            return [
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ];
        }
    }

    public function actualizarCompleto(int $id, array $data): array {
        $sql = "UPDATE usuario SET
            nombre = :nombre,
            apellido = :apellido,
            email = :email,
            contrasena = :contrasena,
            fecha_nacimiento = :fecha_nacimiento,
            telefono = :telefono,
            direccion = :direccion,
            rol = :rol,
            fecha_registro = :fecha_registro,
            estado = :estado
        WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        return ['mensaje' => 'Usuario actualizado correctamente'];
    }

    public function actualizarParcial(int $id, array $data): array {
    if (empty($data)) {
        return ['error' => 'No se enviaron campos para actualizar'];
    }

    $set = [];
    foreach ($data as $campo => $valor) {
        $set[] = "$campo = :$campo";
    }

    $sql = "UPDATE usuario SET " . implode(', ', $set) . " WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    foreach ($data as $campo => $valor) {
        $stmt->bindValue(":$campo", $valor);
    }
    $stmt->execute();

    return ['mensaje' => 'Usuario actualizado parcialmente'];
    }

    public function eliminar(int $id): array {
        $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return ['mensaje' => 'Usuario eliminado correctamente'];
    }
}