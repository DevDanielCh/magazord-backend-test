<?php

namespace App\Core;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../../vendor/autoload.php';

class FaderimEngine
{
    private static ?FaderimEngine $instance = null;

    private EntityManager $entityManager;

    private function __construct()
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../Model'],
            isDevMode: true
        );

        // Esses parametros default só se faz em app de testes como esse, em casos reais nunca devemos colocar essa informações hardcoded
        $connectionParams = [
            'driver'   => 'pdo_pgsql',
            'host'     => getenv('DB_HOST') ?: 'db',
            'port'     => getenv('DB_PORT') ?: 5432,
            'dbname'   => getenv('DB_NAME') ?: 'database',
            'user'     => getenv('DB_USER') ?: 'postgres',
            'password' => getenv('DB_PASS') ?: 'postgres',
        ];

        $connection = DriverManager::getConnection($connectionParams, $config);

        $this->entityManager = new EntityManager($connection, $config);
    }

    public static function getInstance(): FaderimEngine
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}
