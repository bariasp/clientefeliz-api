<?php
require_once __DIR__ . '/../controllers/AntecedenteLaboralController.php';

$controller = new AntecedenteLaboralController();

$router->get('/antecedentelaboral', fn() => $controller->obtenerTodos());
$router->get('/antecedentelaboral/{id}', fn($id) => $controller->obtenerPorId((int)$id));
$router->post('/antecedentelaboral', fn() => $controller->crear(json_decode(file_get_contents("php://input"), true)));

// PUT – Actualización completa
$router->put('/antecedentelaboral/{id}', fn($id) =>
    $controller->actualizarCompleto((int)$id, json_decode(file_get_contents("php://input"), true))
);

// PATCH – Actualización parcial
$router->patch('/antecedentelaboral/{id}', fn($id) =>
    $controller->actualizarParcial((int)$id, json_decode(file_get_contents("php://input"), true))
);

$router->delete('/antecedentelaboral/{id}', fn($id) => $controller->eliminar((int)$id));