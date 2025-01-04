<?php
class Router {
    private array $routes = [];

    public function add(string $method, string $path, callable|array $handler): void {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch(string $requestUri, string $requestMethod): void {
        foreach ($this->routes as $route) {
            $params = [];
            if ($route['method'] === strtoupper($requestMethod) && $this->match($route['path'], $requestUri, $params)) {
                call_user_func_array($route['handler'], $params);
                return;
            }
        }
        // Handle 404
        http_response_code(404);
        echo "404 Not Found";
    }

    private function match(string $routePath, string $requestUri, array &$params): bool {
        $routePattern = preg_replace('/\{([\w]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';

        if (preg_match($routePattern, $requestUri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }
        return false;
    }
}