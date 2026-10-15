<?php
/**
 * @var string $title
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand fw-bold">CRUD Pessoas &amp; Contatos</span>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/pessoas">Pessoas</a>
                </li>
            </ul>
        </div>
    </nav>
    <main class="container">
        <?php if (($msg = flash('success')) !== null): ?>
            <div class="alert alert-success"><?= escape($msg) ?></div>
        <?php endif; ?>
        <?php if (($msg = flash('error')) !== null): ?>
            <div class="alert alert-danger"><?= escape($msg) ?></div>
        <?php endif; ?>
        <?= $content ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
