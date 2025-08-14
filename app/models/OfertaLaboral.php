<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/Database.php';

class OfertaLaboral {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    public function obtenerTodas(): array {
        $stmt = $this->pdo->query("SELECT * FROM ofertalaboral");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM ofertalaboral WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(array $data): array {
        try {
            $sql = "INSERT INTO ofertalaboral (
                titulo, descripcion, ubicacion, salario, tipo_contrato,
                fecha_publicacion, fecha_cierre, estado, reclutador_id
            ) VALUES (
                :titulo, :descripcion, :ubicacion, :salario, :tipo_contrato,
                :fecha_publicacion, :fecha_cierre, :estado, :reclutador_id
            )";

            $stmt = $this->pdo->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();

            return ['mensaje' => 'Oferta laboral creada correctamente'];
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
            'titulo', 'descripcion', 'ubicacion', 'salario', 'tipo_contrato',
            'fecha_publicacion', 'fecha_cierre', 'estado', 'reclutador_id'
        ];

        foreach ($camposObligatorios as $campo) {
            if (!isset($data[$campo])) {
                return ['error' => "Falta el campo obligatorio: $campo"];
            }
        }

        $sql = "UPDATE ofertalaboral SET
            titulo = :titulo,
            descripcion = :descripcion,
            ubicacion = :ubicacion,
            salario = :salario,
            tipo_contrato = :tipo_contrato,
            fecha_publicacion = :fecha_publicacion,
            fecha_cierre = :fecha_cierre,
            estado = :estado,
            reclutador_id = :reclutador_id
        WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        return ['mensaje' => 'Oferta laboral actualizada completamente'];
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

        $sql = "UPDATE ofertalaboral SET " . implode(', ', $set) . " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        foreach ($data as $campo => $valor) {
            $stmt->bindValue(":$campo", $valor);
        }
        $stmt->execute();

        return ['mensaje' => 'Oferta laboral actualizada parcialmente'];
    }

    public function eliminar(int $id): array {
        $stmt = $this->pdo->prepare("DELETE FROM ofertalaboral WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return ['mensaje' => 'Oferta laboral eliminada correctamente'];
    }
}