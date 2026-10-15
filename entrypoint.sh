#!/bin/bash
set -e

echo "Aguardando o banco de dados iniciar..."
sleep 5

echo "Instalando dependências do Composer..."
composer install --no-interaction --optimize-autoloader

echo "Preparando diretório de proxies do Doctrine..."
mkdir -p /tmp/doctrine-proxies
chmod 777 /tmp/doctrine-proxies

echo "Criando/atualizando o schema do banco de dados via Doctrine..."
php bin/db-setup.php

echo "Iniciando o servidor Apache..."
exec apache2-foreground