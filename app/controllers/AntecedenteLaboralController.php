<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/AntecedenteLaboral.php';

class AntecedenteLaboralController {
    public function obtenerTodos(): void {
        $antecedente = new AntecedenteLaboral();
        echo json_encode($antecedente->obtenerTodos());
    }

    public function obtenerPorId(int $id): void {
        $antecedente = new AntecedenteLaboral();
        $resultado = $antecedente->obtenerPorId($id);
        echo json_encode($resultado ?: ['error' => 'Antecedente no encontrado']);
    }

    public function crear(array $data): void {
        $campos = [
            'candidato_id', 'empresa', 'cargo', 'funciones',
            'fecha_inicio', 'fecha_termino'
        ];

        foreach ($campos as $campo) {
            if (empty($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo: $campo"]);
                return;
            }
        }

        $antecedente = new AntecedenteLaboral();
        echo json_encode($antecedente->crear($data));
    }

    // PUT – Actualización completa
    public function actualizarCompleto(int $id, array $data): void {
        $campos = [
            'candidato_id', 'empresa', 'cargo', 'funciones',
            'fecha_inicio', 'fecha_termino'
        ];

        foreach ($campos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo obligatorio: $campo"]);
                return;
            }
        }

        $antecedente = new AntecedenteLaboral();
        echo json_encode($antecedente->actualizarCompleto($id, $data));
    }

    // PATCH – Actualización parcial
    public function actualizarParcial(int $id, array $data): void {
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se enviaron campos para actualizar']);
            return;
        }

        $antecedente = new AntecedenteLaboral();
        echo json_encode($antecedente->actualizarParcial($id, $data));
    }

    public function eliminar(int $id): void {
        $antecedente = new AntecedenteLaboral();
        echo json_encode($antecedente->eliminar($id));
    }
}