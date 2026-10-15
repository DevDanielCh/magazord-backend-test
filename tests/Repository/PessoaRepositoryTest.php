<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Model\Pessoa;
use App\Tests\DatabaseTestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class PessoaRepositoryTest extends DatabaseTestCase
{
    public function testPersistAndFind(): void
    {
        $person = $this->createPerson('Yuri Mateus Bryan Teixeira', '73409512969');

        $this->em->persist($person);
        $this->em->flush();
        $this->em->clear();

        $found = $this->em->find(Pessoa::class, $person->getId());

        $this->assertNotNull($found);
        $this->assertSame('Yuri Mateus Bryan Teixeira', $found->getNome());
    }

    public function testDuplicateCpfIsRejected(): void
    {
        $this->em->persist($this->createPerson('Yuri Mateus Bryan Teixeira', '73409512969'));
        $this->em->flush();

        $this->expectException(UniqueConstraintViolationException::class);

        $this->em->persist($this->createPerson('Outra Pessoa', '73409512969'));
        $this->em->flush();
    }

    public function testFulltextSearchByName(): void
    {
        $this->em->persist($this->createPerson('Yuri Mateus Bryan Teixeira', '73409512969'));
        $this->em->persist($this->createPerson('Bruno Souza', '11144477735'));
        $this->em->flush();
        $this->em->clear();

        $repository = $this->em->getRepository(Pessoa::class);

        $yuri = $repository->searchByName('yuri');
        $this->assertCount(1, $yuri);
        $this->assertSame('Yuri Mateus Bryan Teixeira', $yuri[0]->getNome());

        $souza = $repository->searchByName('souza');
        $this->assertCount(1, $souza);
        $this->assertSame('Bruno Souza', $souza[0]->getNome());

        $this->assertSame([], $repository->searchByName('inexistente'));
    }

    public function testEmptySearchReturnsAll(): void
    {
        $this->em->persist($this->createPerson('Yuri Mateus Bryan Teixeira', '73409512969'));
        $this->em->flush();
        $this->em->clear();

        $result = $this->em->getRepository(Pessoa::class)->searchByName('   ');

        $this->assertCount(1, $result);
    }

    private function createPerson(string $name, string $cpf): Pessoa
    {
        $person = new Pessoa();
        $person->setNome($name);
        $person->setCpf($cpf);

        return $person;
    }
}
