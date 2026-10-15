<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\Contato;
use App\Model\Pessoa;
use App\Model\TipoContato;
use PHPUnit\Framework\TestCase;

class ContatoTest extends TestCase
{
    public function testToArray(): void
    {
        $person = new Pessoa();
        $person->setId(7);

        $contact = new Contato();
        $contact->setTipo(TipoContato::Email);
        $contact->setDescricao('yuri_teixeira@galvao.com');
        $contact->setPessoa($person);

        $data = $contact->toArray();

        $this->assertSame(TipoContato::Email->value, $data['tipo']);
        $this->assertSame('Email', $data['tipo_label']);
        $this->assertSame('yuri_teixeira@galvao.com', $data['descricao']);
        $this->assertSame(7, $data['pessoa_id']);
    }
}
