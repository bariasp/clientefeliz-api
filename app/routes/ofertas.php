<?php
require_once __DIR__ . '/../controllers/OfertaLaboralController.php';

$controller = new OfertaLaboralController();

$router->get('/ofertalaboral', fn() => $controller->obtenerTodas());
$router->get('/ofertalaboral/{id}', fn($id) => $controller->obtenerPorId((int)$id));
$router->post('/ofertalaboral', fn() => $controller->crear(json_decode(file_get_contents("php://input"), true)));

// PUT – Actualización completa
$router->put('/ofertalaboral/{id}', fn($id) =>
    $controller->actualizarCompleto((int)$id, json_decode(file_get_contents("php://input"), true))
);

// PATCH – Actualización parcial
$router->patch('/ofertalaboral/{id}', fn($id) =>
    $controller->actualizarParcial((int)$id, json_decode(file_get_contents("php://input"), true))
);

$router->delete('/ofertalaboral/{id}', fn($id) => $controller->eliminar((int)$id));