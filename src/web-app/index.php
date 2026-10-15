<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\FaderimEngine;

$entityManager = FaderimEngine::getInstance()->getEntityManager();

echo 'OK';