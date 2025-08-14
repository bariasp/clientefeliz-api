<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/AntecedenteAcademico.php';

class AntecedenteAcademicoController {
    public function obtenerTodos(): void {
        $antecedente = new AntecedenteAcademico();
        echo json_encode($antecedente->obtenerTodos());
    }

    public function obtenerPorId(int $id): void {
        $antecedente = new AntecedenteAcademico();
        $resultado = $antecedente->obtenerPorId($id);
        echo json_encode($resultado ?: ['error' => 'Antecedente no encontrado']);
    }

    public function crear(array $data): void {
        $campos = [
            'candidato_id', 'institucion', 'titulo_obtenido',
            'anio_ingreso', 'anio_egreso'
        ];

        foreach ($campos as $campo) {
            if (empty($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo: $campo"]);
                return;
            }
        }

        $antecedente = new AntecedenteAcademico();
        echo json_encode($antecedente->crear($data));
    }

    // PUT – Actualización completa
    public function actualizarCompleto(int $id, array $data): void {
        $campos = [
            'candidato_id', 'institucion', 'titulo_obtenido',
            'anio_ingreso', 'anio_egreso'
        ];

        foreach ($campos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo obligatorio: $campo"]);
                return;
            }
        }

        $antecedente = new AntecedenteAcademico();
        echo json_encode($antecedente->actualizarCompleto($id, $data));
    }

    // PATCH – Actualización parcial
    public function actualizarParcial(int $id, array $data): void {
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se enviaron campos para actualizar']);
            return;
        }

        $antecedente = new AntecedenteAcademico();
        echo json_encode($antecedente->actualizarParcial($id, $data));
    }

    public function eliminar(int $id): void {
        $antecedente = new AntecedenteAcademico();
        echo json_encode($antecedente->eliminar($id));
    }
}