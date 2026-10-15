<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Utils/helpers.php';

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$router = require __DIR__ . '/Core/Routes/routes.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/');

try {
    $router->dispatch($method, $path);
} catch (EntityNotFoundException) {
    notFound('Registro não encontrado.');
} catch (UniqueConstraintViolationException) {
    http_response_code(409);
    view('erro', ['message' => 'Violação de unicidade: registro já existe.'], '409');
} catch (Throwable $error) {
    error_log($error->getMessage() . "\n" . $error->getTraceAsString());
    http_response_code(500);
    view('erro', ['message' => 'Erro interno no servidor.'], '500');
}
