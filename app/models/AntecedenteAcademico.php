<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/Database.php';

class AntecedenteAcademico {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("SELECT * FROM antecedenteacademico");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM antecedenteacademico WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(array $data): array {
        try {
            $sql = "INSERT INTO antecedenteacademico (
                candidato_id, institucion, titulo_obtenido,
                anio_ingreso, anio_egreso
            ) VALUES (
                :candidato_id, :institucion, :titulo_obtenido,
                :anio_ingreso, :anio_egreso
            )";

            $stmt = $this->pdo->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();

            return ['mensaje' => 'Antecedente académico creado correctamente'];
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
            'candidato_id', 'institucion', 'titulo_obtenido',
            'anio_ingreso', 'anio_egreso'
        ];

        foreach ($camposObligatorios as $campo) {
            if (!isset($data[$campo])) {
                return ['error' => "Falta el campo obligatorio: $campo"];
            }
        }

        $sql = "UPDATE antecedenteacademico SET
            candidato_id = :candidato_id,
            institucion = :institucion,
            titulo_obtenido = :titulo_obtenido,
            anio_ingreso = :anio_ingreso,
            anio_egreso = :anio_egreso
        WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        return ['mensaje' => 'Antecedente académico actualizado completamente'];
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

        $sql = "UPDATE antecedenteacademico SET " . implode(', ', $set) . " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        foreach ($data as $campo => $valor) {
            $stmt->bindValue(":$campo", $valor);
        }
        $stmt->execute();

        return ['mensaje' => 'Antecedente académico actualizado parcialmente'];
    }

    public function eliminar(int $id): array {
        $stmt = $this->pdo->prepare("DELETE FROM antecedenteacademico WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return ['mensaje' => 'Antecedente académico eliminado correctamente'];
    }
}