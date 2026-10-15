<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\Contato;
use App\Model\Pessoa;
use App\Model\TipoContato;
use PHPUnit\Framework\TestCase;

class PessoaTest extends TestCase
{
    public function testNormalizarCpfStripsPunctuation(): void
    {
        $this->assertSame('73409512969', Pessoa::normalizarCpf('734.095.129-69'));
    }

    public function testCpfValidoAcceptsRealCpf(): void
    {
        $this->assertTrue(Pessoa::cpfValido('734.095.129-69'));
        $this->assertTrue(Pessoa::cpfValido('73409512969'));
    }

    public function testCpfValidoRejectsInvalidCpfs(): void
    {
        $this->assertFalse(Pessoa::cpfValido('11111111111'));
        $this->assertFalse(Pessoa::cpfValido('123'));
        $this->assertFalse(Pessoa::cpfValido(''));
        $this->assertFalse(Pessoa::cpfValido('73409512968'));
    }

    public function testAddContatoSyncsBothSides(): void
    {
        $person = new Pessoa();
        $contact = new Contato();

        $person->addContato($contact);

        $this->assertTrue($person->getContatos()->contains($contact));
        $this->assertSame($person, $contact->getPessoa());
    }

    public function testRemoveContatoRemovesFromCollection(): void
    {
        $person = new Pessoa();
        $contact = new Contato();
        $person->addContato($contact);

        $person->removeContato($contact);

        $this->assertFalse($person->getContatos()->contains($contact));
        $this->assertSame($person, $contact->getPessoa());
    }

    public function testToArray(): void
    {
        $person = new Pessoa();
        $person->setId(7);
        $person->setNome('Yuri Mateus Bryan Teixeira');
        $person->setCpf('73409512969');

        $this->assertSame(
            ['id' => 7, 'nome' => 'Yuri Mateus Bryan Teixeira', 'cpf' => '73409512969'],
            $person->toArray()
        );
    }

    public function testEnumTipoContatoHasLabel(): void
    {
        $this->assertSame('Telefone', TipoContato::Telefone->label());
        $this->assertSame('Email', TipoContato::Email->label());
    }
}
