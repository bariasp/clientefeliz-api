<?php
require_once __DIR__ . '/../controllers/PostulacionController.php';

$controller = new PostulacionController();

$router->get('/postulacion', fn() => $controller->obtenerTodas());
$router->get('/postulacion/{id}', fn($id) => $controller->obtenerPorId((int)$id));
$router->post('/postulacion', fn() => $controller->crear(json_decode(file_get_contents("php://input"), true)));

// PUT – Actualización completa (requiere todos los campos)
$router->put('/postulacion/{id}', fn($id) =>
    $controller->actualizarCompleto((int)$id, json_decode(file_get_contents("php://input"), true))
);

// PATCH – Actualización parcial (solo campos enviados)
$router->patch('/postulacion/{id}', fn($id) =>
    $controller->actualizarParcial((int)$id, json_decode(file_get_contents("php://input"), true))
);

$router->delete('/postulacion/{id}', fn($id) => $controller->eliminar((int)$id));