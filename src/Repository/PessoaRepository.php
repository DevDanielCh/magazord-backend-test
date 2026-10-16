<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PessoaRepository extends EntityRepository
{
    public function searchByName(string $nameSearch): array
    {
        $nameSearch = trim($nameSearch);

        if ($nameSearch === '') {
            return $this->findAll();
        }

        $connection = $this->getEntityManager()->getConnection();

        $fulltextSql = <<<'SQL'
            SELECT pessoa.id
            FROM pessoa pessoa
            WHERE to_tsvector('portuguese', pessoa.nome) @@ plainto_tsquery('portuguese', :q)
            ORDER BY pessoa.nome ASC
        SQL;

        $ids = $connection->fetchFirstColumn($fulltextSql, ['q' => $nameSearch]);

        if ($ids === []) {
            $likeSql = <<<'SQL'
                SELECT pessoa.id
                FROM pessoa pessoa
                WHERE pessoa.nome ILIKE :q ESCAPE '\'
                ORDER BY pessoa.nome ASC
            SQL;

            $ids = $connection->fetchFirstColumn($likeSql, [
                'q' => '%' . addcslashes($nameSearch, '%_\\') . '%',
            ]);
        }

        if ($ids === []) {
            return [];
        }

        return $this->findBy(['id' => $ids], ['nome' => 'ASC']);
    }
}
