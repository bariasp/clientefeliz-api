<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';

$controller = new UsuarioController();

$router->get('/usuario', fn() => $controller->obtenerTodos());
$router->get('/usuario/{id}', fn($id) => $controller->obtenerPorId((int)$id));
$router->post('/usuario', fn() => $controller->crear(json_decode(file_get_contents("php://input"), true)));

// PUT → Actualización completa (requiere todos los campos)
$router->put('/usuario/{id}', fn($id) =>
    $controller->actualizarCompleto((int)$id, json_decode(file_get_contents("php://input"), true))
);

// PATCH → Actualización parcial (solo campos enviados)
$router->patch('/usuario/{id}', fn($id) =>
    $controller->actualizarParcial((int)$id, json_decode(file_get_contents("php://input"), true))
);

$router->delete('/usuario/{id}', fn($id) => $controller->eliminar((int)$id));