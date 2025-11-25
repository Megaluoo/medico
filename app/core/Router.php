<?php
class Router
{
    private array $routes = [];

    public function get(string $path, callable $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, callable $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
            return;
        }

        http_response_code(404);
        echo "<div class='min-h-screen flex items-center justify-center bg-slate-50 text-slate-900'>";
        echo "<div class='text-center'>";
        echo "<h1 class='text-4xl font-semibold mb-4'>404</h1>";
        echo "<p class='text-lg text-slate-500'>Página no encontrada</p>";
        echo "</div></div>";
    }
}
