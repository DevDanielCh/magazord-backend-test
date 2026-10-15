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

        // /tmp tem sticky bit, então proxies gerados pelo CLI (root) não podem
        // ser sobrescritos pelo Apache (www-data). Usamos um dir dedicado 777.
        $proxyDir = sys_get_temp_dir() . '/doctrine-proxies';

        if (!is_dir($proxyDir)) {
            @mkdir($proxyDir, 0777, true);
        }

        $config->setProxyDir($proxyDir);
        $config->setProxyNamespace('App\Proxies');

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

    public static function reset(): void
    {
        self::$instance = null;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}
