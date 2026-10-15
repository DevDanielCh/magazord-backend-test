<?php

namespace App\Core\Routes;

use App\Controllers\BaseController;
use App\Controllers\ContatoController;
use App\Controllers\PessoaController;

final class ControllerFactory
{
    public const PESSOAS = 'pessoas';
    public const CONTATOS = 'contatos';

    public static function create(string $controller, array $params): BaseController
    {
        $id = isset($params['id']) ? (int) $params['id'] : null;
        $personId = isset($params['personId']) ? (int) $params['personId'] : null;

        return match ($controller) {
            self::PESSOAS => new PessoaController($id),
            self::CONTATOS => new ContatoController($personId, $id),
            default => throw new \InvalidArgumentException(
                "Controller desconhecido: {$controller}"
            ),
        };
    }
}
