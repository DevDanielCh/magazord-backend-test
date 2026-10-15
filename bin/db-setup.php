<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\FaderimEngine;

$entityManager = FaderimEngine::getInstance()->getEntityManager();

$entityManager->getConnection()->executeStatement(<<<'SQL'
    CREATE INDEX IF NOT EXISTS pessoa_nome_fts
    ON pessoa USING GIN (to_tsvector('portuguese', nome))
SQL);

echo "Schema atualizado e índice fulltext pronto.\n";
