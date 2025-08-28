<?php
// backend/app/Core/Router.php

class Router {
    private $routes = [];
    private $requestMethod;
    private $requestUri;

    public function __construct() {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function run() {
        foreach ($this->routes as $route) {
            $pattern = $this->buildPattern($route['path']);
            
            if ($this->requestMethod === $route['method'] && preg_match($pattern, $this->requestUri, $matches)) {
                array_shift($matches);
                call_user_func_array($route['handler'], $matches);
                return;
            }
        }

        // إذا لم يتم العثور على أي route
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
    }

    private function buildPattern($path) {
        return '#^' . preg_replace('#\{[a-z]+\}#', '([^/]+)', $path) . '$#';
    }
}
?>