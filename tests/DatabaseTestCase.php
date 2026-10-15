<?php

declare(strict_types=1);

namespace App\Tests;

use App\Core\FaderimEngine;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        FaderimEngine::reset();
        $this->em = FaderimEngine::getInstance()->getEntityManager();

        $this->em->getConnection()->executeStatement('DELETE FROM contato');
        $this->em->getConnection()->executeStatement('DELETE FROM pessoa');
    }

    protected function tearDown(): void
    {
        $this->em->clear();
    }
}
