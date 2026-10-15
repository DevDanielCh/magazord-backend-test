#!/bin/bash
set -e

echo "Aguardando o banco de dados iniciar..."
sleep 5

echo "Instalando dependências do Composer..."
composer install --no-interaction --optimize-autoloader

echo "Criando/atualizando o schema do banco de dados via Doctrine..."
php -r 'require "vendor/autoload.php"; $em = App\Core\FaderimEngine::getInstance()->getEntityManager(); (new Doctrine\ORM\Tools\SchemaTool($em))->updateSchema($em->getMetadataFactory()->getAllMetadata());'

echo "Iniciando o servidor Apache..."
exec apache2-foreground