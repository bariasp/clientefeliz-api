<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Postulacion.php';

class PostulacionController {
    public function obtenerTodas(): void {
        $postulacion = new Postulacion();
        echo json_encode($postulacion->obtenerTodas());
    }

    public function obtenerPorId(int $id): void {
        $postulacion = new Postulacion();
        $resultado = $postulacion->obtenerPorId($id);
        echo json_encode($resultado ?: ['error' => 'Postulación no encontrada']);
    }

    public function crear(array $data): void {
        $campos = [
            'candidato_id', 'oferta_laboral_id', 'estado_postulacion',
            'comentario', 'fecha_postulacion', 'fecha_actualizacion'
        ];

        foreach ($campos as $campo) {
            if (empty($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo: $campo"]);
                return;
            }
        }

        $postulacion = new Postulacion();
        echo json_encode($postulacion->crear($data));
    }

    // PUT – Actualización completa
    public function actualizarCompleto(int $id, array $data): void {
        $campos = [
            'candidato_id', 'oferta_laboral_id', 'estado_postulacion',
            'comentario', 'fecha_postulacion', 'fecha_actualizacion'
        ];

        foreach ($campos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo obligatorio: $campo"]);
                return;
            }
        }

        $postulacion = new Postulacion();
        echo json_encode($postulacion->actualizarCompleto($id, $data));
    }

    // PATCH – Actualización parcial
    public function actualizarParcial(int $id, array $data): void {
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se enviaron campos para actualizar']);
            return;
        }

        $postulacion = new Postulacion();
        echo json_encode($postulacion->actualizarParcial($id, $data));
    }

    public function eliminar(int $id): void {
        $postulacion = new Postulacion();
        echo json_encode($postulacion->eliminar($id));
    }
}