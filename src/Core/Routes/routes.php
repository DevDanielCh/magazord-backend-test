<?php

use App\Core\Routes\ControllerFactory;
use App\Core\Routes\Router;

$router = new Router();

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
