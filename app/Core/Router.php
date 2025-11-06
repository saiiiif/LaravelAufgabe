<?php

namespace App\Core;

class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->register('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->register('POST', $path, $handler);
    }

    private function register(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        $handler = $this->match($method, $path);

        if ($handler === null) {
            http_response_code(404);
            echo 'Page not found';
            return;
        }

        $handler();
    }

    private function match(string $method, string $path): ?callable
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                return fn () => $handler(...$matches);
            }
        }

        return null;
    }
}
