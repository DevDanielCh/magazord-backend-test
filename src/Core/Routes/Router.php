<?php

namespace App\Core\Routes;

final class Router
{
    private array $routes = [];

    public function page(
        string $path,
        string $controller,
        string $title,
        string $titleAdd,
        string $titleEdit,
        string $redirect,
    ): self {
        $this->routes[$path] = new Route(
            path: $path,
            controller: $controller,
            title: $title,
            titleAdd: $titleAdd,
            titleEdit: $titleEdit,
            redirect: $redirect,
        );

        return $this;
    }

    public function dispatch(string $method, string $path): never
    {
        $path = $path === '' ? '/pessoas' : $path;

        $route = $this->routes[$path] ?? null;

        if ($route === null) {
            notFound();
        }

        $action = (string) ($_GET['action'] ?? 'list');

        $controller = ControllerFactory::create($route->controller, [
            'id' => $this->param('id'),
            'personId' => $this->param('pessoa_id'),
        ]);

        $controller->process($route, $action, $method);
    }

    private function param(string $name): ?int
    {
        $value = $_POST[$name] ?? $_GET[$name] ?? null;

        return $value !== null && $value !== '' ? (int) $value : null;
    }
}
