<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Model\Contato;
use App\Model\Pessoa;
use App\Model\TipoContato;
use App\Tests\DatabaseTestCase;

class ContatoRepositoryTest extends DatabaseTestCase
{
    public function testCascadePersistViaPerson(): void
    {
        $person = new Pessoa();
        $person->setNome('Yuri Mateus Bryan Teixeira');
        $person->setCpf('73409512969');

        $contact = new Contato();
        $contact->setTipo(TipoContato::Telefone);
        $contact->setDescricao('(47) 98938-2203');
        $person->addContato($contact);

        $this->em->persist($person);
        $this->em->flush();
        $this->em->clear();

        $contacts = $this->em->getRepository(Contato::class)->findBy(['pessoa' => $person]);

        $this->assertCount(1, $contacts);
        $this->assertSame($person->getId(), $contacts[0]->getPessoa()->getId());
    }

    public function testDeletePersonRemovesContacts(): void
    {
        $person = new Pessoa();
        $person->setNome('Yuri Mateus Bryan Teixeira');
        $person->setCpf('73409512969');

        $contact = new Contato();
        $contact->setTipo(TipoContato::Email);
        $contact->setDescricao('yuri_teixeira@galvao.com');
        $person->addContato($contact);

        $this->em->persist($person);
        $this->em->flush();
        $this->em->clear();

        $person = $this->em->find(Pessoa::class, $person->getId());
        $this->em->remove($person);
        $this->em->flush();

        $total = (int) $this->em->getConnection()
            ->fetchOne('SELECT count(*) FROM contato');

        $this->assertSame(0, $total);
    }

    public function testDeleteContactRemovesOnlyIt(): void
    {
        $person = new Pessoa();
        $person->setNome('Yuri Mateus Bryan Teixeira');
        $person->setCpf('73409512969');

        $phone = new Contato();
        $phone->setTipo(TipoContato::Telefone);
        $phone->setDescricao('(47) 98938-2203');

        $email = new Contato();
        $email->setTipo(TipoContato::Email);
        $email->setDescricao('yuri_teixeira@galvao.com');

        $person->addContato($phone);
        $person->addContato($email);

        $this->em->persist($person);
        $this->em->flush();
        $this->em->clear();

        $this->em->remove($this->em->find(Contato::class, $phone->getId()));
        $this->em->flush();

        $remaining = $this->em->getRepository(Contato::class)->findAll();

        $this->assertCount(1, $remaining);
        $this->assertSame('yuri_teixeira@galvao.com', $remaining[0]->getDescricao());
    }
}
