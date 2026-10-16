<?php

use App\Core\Routes\ControllerFactory;
use App\Core\Routes\Router;

$router = new Router();

$router->get('/health', function (): void {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
});

$router->page(
    path: '/pessoas',
    controller: ControllerFactory::PESSOAS,
    title: 'Pessoas',
    titleAdd: 'Nova Pessoa',
    titleEdit: 'Editar Pessoa',
    redirect: '/pessoas',
);

$router->page(
    path: '/contatos',
    controller: ControllerFactory::CONTATOS,
    title: 'Contatos',
    titleAdd: 'Novo Contato',
    titleEdit: 'Editar Contato',
    redirect: '/contatos?pessoa_id={personId}',
);

return $router;
