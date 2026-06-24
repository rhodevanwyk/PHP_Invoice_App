<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, string $controller, string $action): void
    {
        // CONVERT {PARAM TO NAMED REGEX GROUPS
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<\1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        $this->routes[] = [
            'method' => strtoupper($method),
            'regex' => $regex,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        // STRIP QUERY STRING
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }
            if (preg_match($route['regex'], $uri, $matches)) {
                // EXTRACT NAMED PARAMS ONLY
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $controllerClass = $route['controller'];
                $action = $route['action'];
                $controller = new $controllerClass();
                // INJECT DEPENDENCIES IF NEEDED LATER THO
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }
        // ERROR 404
        http_response_code(404);
        echo '404 Page Not Found';
    }
}