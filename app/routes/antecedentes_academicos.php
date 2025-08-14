<?php
require_once __DIR__ . '/../controllers/AntecedenteAcademicoController.php';

$controller = new AntecedenteAcademicoController();

$router->get('/antecedenteacademico', fn() => $controller->obtenerTodos());
$router->get('/antecedenteacademico/{id}', fn($id) => $controller->obtenerPorId((int)$id));
$router->post('/antecedenteacademico', fn() => $controller->crear(json_decode(file_get_contents("php://input"), true)));

// PUT – Actualización completa
$router->put('/antecedenteacademico/{id}', fn($id) =>
    $controller->actualizarCompleto((int)$id, json_decode(file_get_contents("php://input"), true))
);

// PATCH – Actualización parcial
$router->patch('/antecedenteacademico/{id}', fn($id) =>
    $controller->actualizarParcial((int)$id, json_decode(file_get_contents("php://input"), true))
);

$router->delete('/antecedenteacademico/{id}', fn($id) => $controller->eliminar((int)$id));