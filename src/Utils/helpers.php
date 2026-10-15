<?php

declare(strict_types=1);

function escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function view(string $template, array $data = [], string $title = 'CRUD Pessoas e Contatos'): void
{
    extract($data, EXTR_SKIP);

    ob_start();
    require __DIR__ . '/../Views/' . $template . '.php';
    $content = (string) ob_get_clean();

    require __DIR__ . '/../Views/_layout.php';
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$type] = $message;

        return null;
    }

    $value = $_SESSION['flash'][$type] ?? null;
    unset($_SESSION['flash'][$type]);

    return $value;
}

function old(string $field, string $default = ''): string
{
    return (string) ($_SESSION['old'][$field] ?? $default);
}

function clearOld(): void
{
    unset($_SESSION['old']);
}

function notFound(string $message = 'Página não encontrada.'): never
{
    http_response_code(404);
    view('erro', ['message' => $message], '404');
    exit;
}

function formatCpf(string $cpf): string
{
    $cpf = preg_replace('/\D+/', '', $cpf) ?? '';

    if (strlen($cpf) !== 11) {
        return $cpf;
    }

    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
}
