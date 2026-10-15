<?php

namespace App\Core\Routes;

final class Route
{
    public function __construct(
        public readonly string $path,
        public readonly string $controller,
        public readonly string $title,
        public readonly string $titleAdd,
        public readonly string $titleEdit,
        public readonly string $redirect,
    ) {}
}
