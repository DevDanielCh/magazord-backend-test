<?php

namespace App\Model;

use App\Repository\ContatoRepository;
use Doctrine\ORM\Mapping as ORM;

// Isso aqui só da pra fazer no PHP 8, nas versões antigas é constante na veia e segue a vida
enum TipoContato: int
{
    case Telefone = 1;
    case Email = 2;

    public function label(): string
    {
        return match ($this) {
            self::Telefone => 'Telefone',
            self::Email    => 'Email',
        };
    }
}


#[ORM\Entity(repositoryClass: ContatoRepository::class)]
#[ORM\Table(name: 'contato')]
class Contato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'smallint', enumType: TipoContato::class)]
    private TipoContato $tipo;

    #[ORM\Column(length: 255)]
    private string $descricao;

    #[ORM\ManyToOne(targetEntity: Pessoa::class, inversedBy: 'contatos')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Pessoa $pessoa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): TipoContato
    {
        return $this->tipo;
    }

    public function setTipo(TipoContato $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'tipo'        => $this->tipo->value,
            'tipo_label'  => $this->tipo->label(),
            'descricao'   => $this->descricao,
            'pessoa_id'   => $this->pessoa->getId(),
        ];
    }
}
