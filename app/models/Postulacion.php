<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/Database.php';

class Postulacion {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    public function obtenerTodas(): array {
        $stmt = $this->pdo->query("SELECT * FROM postulacion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM postulacion WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // POST – Crear nueva postulación
    public function crear(array $data): array {
        try {
            $sql = "INSERT INTO postulacion (
                candidato_id, oferta_laboral_id, estado_postulacion,
                comentario, fecha_postulacion, fecha_actualizacion
            ) VALUES (
                :candidato_id, :oferta_laboral_id, :estado_postulacion,
                :comentario, :fecha_postulacion, :fecha_actualizacion
            )";

            $stmt = $this->pdo->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();

            return ['mensaje' => 'Postulación creada correctamente'];
        } catch (PDOException $e) {
            return [
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ];
        }
    }

    // PUT – Actualización completa
    public function actualizarCompleto(int $id, array $data): array {
        $camposObligatorios = [
            'candidato_id', 'oferta_laboral_id', 'estado_postulacion',
            'comentario', 'fecha_postulacion', 'fecha_actualizacion'
        ];

        foreach ($camposObligatorios as $campo) {
            if (!isset($data[$campo])) {
                return ['error' => "Falta el campo obligatorio: $campo"];
            }
        }

        $sql = "UPDATE postulacion SET
            candidato_id = :candidato_id,
            oferta_laboral_id = :oferta_laboral_id,
            estado_postulacion = :estado_postulacion,
            comentario = :comentario,
            fecha_postulacion = :fecha_postulacion,
            fecha_actualizacion = :fecha_actualizacion
        WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        return ['mensaje' => 'Postulación actualizada completamente'];
    }

    // PATCH – Actualización parcial
    public function actualizarParcial(int $id, array $data): array {
        if (empty($data)) {
            return ['error' => 'No se enviaron campos para actualizar'];
        }

        $set = [];
        foreach ($data as $campo => $valor) {
            $set[] = "$campo = :$campo";
        }

        $sql = "UPDATE postulacion SET " . implode(', ', $set) . " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        foreach ($data as $campo => $valor) {
            $stmt->bindValue(":$campo", $valor);
        }
        $stmt->execute();

        return ['mensaje' => 'Postulación actualizada parcialmente'];
    }

    // DELETE – Eliminar
    public function eliminar(int $id): array {
        $stmt = $this->pdo->prepare("DELETE FROM postulacion WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return ['mensaje' => 'Postulación eliminada correctamente'];
    }
}