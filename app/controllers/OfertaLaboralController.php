<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/OfertaLaboral.php';

class OfertaLaboralController {
    public function obtenerTodas(): void {
        $oferta = new OfertaLaboral();
        echo json_encode($oferta->obtenerTodas());
    }

    public function obtenerPorId(int $id): void {
        $oferta = new OfertaLaboral();
        $resultado = $oferta->obtenerPorId($id);
        echo json_encode($resultado ?: ['error' => 'Oferta no encontrada']);
    }

    public function crear(array $data): void {
        $campos = [
            'titulo', 'descripcion', 'ubicacion', 'salario', 'tipo_contrato',
            'fecha_publicacion', 'fecha_cierre', 'estado', 'reclutador_id'
        ];

        foreach ($campos as $campo) {
            if (empty($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo: $campo"]);
                return;
            }
        }

        $oferta = new OfertaLaboral();
        echo json_encode($oferta->crear($data));
    }

    // PUT – Actualización completa
    public function actualizarCompleto(int $id, array $data): void {
        $campos = [
            'titulo', 'descripcion', 'ubicacion', 'salario', 'tipo_contrato',
            'fecha_publicacion', 'fecha_cierre', 'estado', 'reclutador_id'
        ];

        foreach ($campos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo obligatorio: $campo"]);
                return;
            }
        }

        $oferta = new OfertaLaboral();
        echo json_encode($oferta->actualizarCompleto($id, $data));
    }

    // PATCH – Actualización parcial
    public function actualizarParcial(int $id, array $data): void {
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se enviaron campos para actualizar']);
            return;
        }

        $oferta = new OfertaLaboral();
        echo json_encode($oferta->actualizarParcial($id, $data));
    }

    public function eliminar(int $id): void {
        $oferta = new OfertaLaboral();
        echo json_encode($oferta->eliminar($id));
    }
}