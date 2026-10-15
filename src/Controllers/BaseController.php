<?php

namespace App\Controllers;

use App\Core\FaderimEngine;
use App\Core\Routes\Route;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseController
{
    protected function getEntityManager(): EntityManagerInterface
    {
        return FaderimEngine::getInstance()->getEntityManager();
    }

    abstract public function view(): array;

    abstract public function add(): array;

    abstract public function addPost(): bool;

    abstract public function edit(): array;

    abstract public function editPost(): bool;

    abstract public function delete(): bool;

    final public function process(Route $route, string $action, string $method): never
    {
        $method = strtoupper($method);

        $allowed = match ($action) {
            'list', 'view' => $method === 'GET',
            'add', 'edit' => $method === 'GET' || $method === 'POST',
            'delete' => $method === 'POST',
            default => false,
        };

        if (!$allowed) {
            notFound();
        }

        $result = match ($method . ' ' . $action) {
            'GET list' => $this->view(),
            'GET view' => $this->view(),
            'GET add' => $this->add(),
            'POST add' => $this->addPost(),
            'GET edit' => $this->edit(),
            'POST edit' => $this->editPost(),
            'POST delete' => $this->delete(),
            default => notFound(),
        };

        if (is_bool($result)) {
            if ($result) {
                redirect($this->buildRedirect($route->redirect));
            }

            $result = $action === 'edit' ? $this->edit() : $this->add();
        }

        view($this->template($route, $action), $result, $this->title($route, $action));
        exit;
    }

    protected function template(Route $route, string $action): string
    {
        return match ($action) {
            'add', 'edit' => $route->controller . '/form',
            default => $route->controller . '/index',
        };
    }

    private function title(Route $route, string $action): string
    {
        return match ($action) {
            'add' => $route->titleAdd,
            'edit' => $route->titleEdit,
            default => $route->title,
        };
    }

    private function buildRedirect(string $redirect): string
    {
        if (str_contains($redirect, '{personId}') && method_exists($this, 'contactPersonId')) {
            $redirect = str_replace(
                '{personId}',
                (string) $this->contactPersonId(),
                $redirect
            );
        }

        return $redirect;
    }
}
