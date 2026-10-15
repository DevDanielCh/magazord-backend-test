<?php

namespace App\Controllers;

use App\Core\Routes\Route;
use App\Model\Contato;
use App\Model\Pessoa;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;

class PessoaController extends BaseController
{

    public function __construct(
        private readonly ?int $id = null,
    ) {
    }

    public function view(): array
    {
        if ($this->id !== null) {
            $person = $this->findPerson();

            $contacts = $this->getEntityManager()
                ->getRepository(Contato::class)
                ->findBy(['pessoa' => $person], ['id' => 'ASC']);

            return ['person' => $person, 'contacts' => $contacts];
        }

        $query = trim((string) ($_GET['q'] ?? ''));

        $repository = $this->getEntityManager()->getRepository(Pessoa::class);
        $people = $query !== ''
            ? $repository->searchByName($query)
            : $repository->findAll();

        return ['people' => $people, 'query' => $query];
    }

    public function add(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            clearOld();
        }

        $person = new Pessoa();
        $person->setNome(old('nome'));
        $person->setCpf(old('cpf'));

        return ['person' => $person];
    }

    public function addPost(): bool
    {
        $errors = $this->validate($_POST);

        if ($errors !== []) {
            $_SESSION['old'] = $_POST;
            flash('error', implode(' ', $errors));

            return false;
        }

        $person = new Pessoa();
        $person->setNome(trim((string) $_POST['nome']));
        $person->setCpf(Pessoa::normalizarCpf((string) $_POST['cpf']));

        if ($this->getEntityManager()->getRepository(Pessoa::class)->findOneBy(['cpf' => $person->getCpf()]) !== null) {
            $_SESSION['old'] = $_POST;
            flash('error', 'CPF já cadastrado.');

            return false;
        }

        $this->getEntityManager()->persist($person);

        try {
            $this->getEntityManager()->flush();
        } catch (UniqueConstraintViolationException) {
            $_SESSION['old'] = $_POST;
            flash('error', 'CPF já cadastrado.');

            return false;
        }

        clearOld();
        flash('success', 'Pessoa criada com sucesso.');

        return true;
    }

    public function edit(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            clearOld();
        }

        return ['person' => $this->findPerson()];
    }

    public function editPost(): bool
    {
        $person = $this->findPerson();
        $errors = $this->validate($_POST);

        if ($errors !== []) {
            $_SESSION['old'] = $_POST;
            flash('error', implode(' ', $errors));

            return false;
        }

        $cpf = Pessoa::normalizarCpf((string) $_POST['cpf']);
        $existing = $this->getEntityManager()->getRepository(Pessoa::class)->findOneBy(['cpf' => $cpf]);

        if ($existing !== null && $existing->getId() !== $person->getId()) {
            $_SESSION['old'] = $_POST;
            flash('error', 'CPF já cadastrado.');

            return false;
        }

        $person->setNome(trim((string) $_POST['nome']));
        $person->setCpf($cpf);

        try {
            $this->getEntityManager()->flush();
        } catch (UniqueConstraintViolationException) {
            $_SESSION['old'] = $_POST;
            flash('error', 'CPF já cadastrado.');

            return false;
        }

        clearOld();
        flash('success', 'Pessoa atualizada com sucesso.');

        return true;
    }

    public function delete(): bool
    {
        $person = $this->findPerson();

        $this->getEntityManager()->remove($person);
        $this->getEntityManager()->flush();

        flash('success', 'Pessoa excluída. Os contatos também foram removidos.');

        return true;
    }

    protected function template(Route $route, string $action): string
    {
        if ($action === 'view' && $this->id !== null) {
            return 'contatos/index';
        }

        return parent::template($route, $action);
    }

    private function findPerson(): Pessoa
    {
        $person = $this->id !== null
            ? $this->getEntityManager()->find(Pessoa::class, $this->id)
            : null;

        if ($person === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(
                Pessoa::class,
                ['id' => (string) $this->id]
            );
        }

        return $person;
    }

    private function validate(array $data): array
    {
        $errors = [];

        $name = trim((string) ($data['nome'] ?? ''));

        if ($name === '') {
            $errors[] = 'Nome é obrigatório.';
        } elseif (mb_strlen($name) > 255) {
            $errors[] = 'Nome deve ter no máximo 255 caracteres.';
        }

        $cpf = Pessoa::normalizarCpf((string) ($data['cpf'] ?? ''));

        if ($cpf === '') {
            $errors[] = 'CPF é obrigatório.';
        } elseif (!Pessoa::cpfValido($cpf)) {
            $errors[] = 'CPF inválido.';
        }

        return $errors;
    }
}
