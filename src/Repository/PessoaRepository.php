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

        $sql = <<<'SQL'
            SELECT pessoa.id
            FROM pessoa pessoa
            WHERE to_tsvector('portuguese', pessoa.nome) @@ plainto_tsquery('portuguese', :q)
            ORDER BY pessoa.nome ASC
        SQL;

        $ids = $this->getEntityManager()
            ->getConnection()
            ->fetchFirstColumn($sql, ['q' => $nameSearch]);

        if ($ids === []) {
            return [];
        }

        return $this->findBy(['id' => $ids], ['nome' => 'ASC']);
    }
}
