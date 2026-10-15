<?php

namespace App\Model;

use App\Repository\PessoaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PessoaRepository::class)]
#[ORM\Table(name: 'pessoa')]
class Pessoa {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nome;

    #[ORM\Column(length: 11, unique: true)]
    private string $cpf;

    /**
     * @var Collection<int, Contato>
     */
    #[ORM\OneToMany(
        targetEntity: Contato::class,
        mappedBy: 'pessoa',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $contatos;

    public function __construct()
    {
        $this->contatos = new ArrayCollection();
    }

    public static function normalizarCpf(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf) ?? '';
    }

    public static function cpfValido(string $cpf): bool
    {
        $cpf = self::normalizarCpf($cpf);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf) === 1) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;

            for ($i = 0; $i < $t; $i++) {
                $soma += (int) $cpf[$i] * (($t + 1) - $i);
            }

            $digito = ((10 * $soma) % 11) % 10;

            if ((int) $cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * @return Collection<int, Contato>
     */
    public function getContatos(): Collection
    {
        return $this->contatos;
    }

    public function addContato(Contato $contato): self
    {
        if (!$this->contatos->contains($contato)) {
            $this->contatos->add($contato);
            $contato->setPessoa($this);
        }

        return $this;
    }

    public function removeContato(Contato $contato): self
    {
        $this->contatos->removeElement($contato);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cpf' => $this->cpf
        ];
    }
}