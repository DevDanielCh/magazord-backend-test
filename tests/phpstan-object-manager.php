<?php

declare(strict_types=1);

use App\Core\FaderimEngine;

require_once __DIR__ . '/../vendor/autoload.php';

return FaderimEngine::getInstance()->getEntityManager();
