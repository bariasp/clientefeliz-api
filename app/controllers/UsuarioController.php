<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    public function obtenerTodos(): void {
        $usuario = new Usuario();
        echo json_encode($usuario->obtenerTodos());
    }

    public function obtenerPorId(int $id): void {
        $usuario = new Usuario();
        $resultado = $usuario->obtenerPorId($id);
        echo json_encode($resultado ?: ['error' => 'Usuario no encontrado']);
    }

    public function crear(array $data): void {
        $campos = [
            'nombre', 'apellido', 'email', 'contrasena', 'fecha_nacimiento',
            'telefono', 'direccion', 'rol', 'fecha_registro', 'estado'
        ];

        foreach ($campos as $campo) {
            if (empty($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo: $campo"]);
                return;
            }
        }

        $usuario = new Usuario();
        echo json_encode($usuario->crear($data));
    }

    // PUT – Actualización completa
    public function actualizarCompleto(int $id, array $data): void {
        $campos = [
            'nombre', 'apellido', 'email', 'contrasena', 'fecha_nacimiento',
            'telefono', 'direccion', 'rol', 'fecha_registro', 'estado'
        ];

        foreach ($campos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo obligatorio: $campo"]);
                return;
            }
        }

        $usuario = new Usuario();
        echo json_encode($usuario->actualizarCompleto($id, $data));
    }

    // PATCH – Actualización parcial
    public function actualizarParcial(int $id, array $data): void {
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se enviaron campos para actualizar']);
            return;
        }

        $usuario = new Usuario();
        echo json_encode($usuario->actualizarParcial($id, $data));
    }

    public function eliminar(int $id): void {
        $usuario = new Usuario();
        echo json_encode($usuario->eliminar($id));
    }
}