<?php
declare(strict_types=1);

// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Headers CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight para OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Autoload de Composer 
require_once __DIR__ . '/vendor/autoload.php';

// Instanciar router simple
$router = new class {
    private array $routes = [];

    public function get(string $path, callable $handler) { $this->addRoute('GET', $path, $handler); }
    public function post(string $path, callable $handler) { $this->addRoute('POST', $path, $handler); }
    public function put(string $path, callable $handler) { $this->addRoute('PUT', $path, $handler); }
    public function patch(string $path, callable $handler) { $this->addRoute('PATCH', $path, $handler); }
    public function delete(string $path, callable $handler) { $this->addRoute('DELETE', $path, $handler); }

    private function addRoute(string $method, string $path, callable $handler) {
        $this->routes[$method][$this->normalize($path)] = $handler;
    }

    public function dispatch(string $method, string $uri) {
        $uri = $this->normalize($uri);
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $handler) {
            $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([a-zA-Z0-9_-]+)', $route);
            $regex = '#^' . $pattern . '$#';

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }

    private function normalize(string $uri): string {
        $path = parse_url($uri, PHP_URL_PATH);
        $base = '/clientefeliz-api'; 
        return rtrim(str_replace($base, '', $path), '/');
    }
};

// Cargar todas las rutas por entidad
require_once __DIR__ . '/app/routes/usuarios.php';
require_once __DIR__ . '/app/routes/postulaciones.php';
require_once __DIR__ . '/app/routes/ofertas.php';
require_once __DIR__ . '/app/routes/antecedentes_laborales.php';
require_once __DIR__ . '/app/routes/antecedentes_academicos.php';

// Ejecutar la ruta actual
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error interno del servidor',
        'message' => $e->getMessage()
    ]);
}
