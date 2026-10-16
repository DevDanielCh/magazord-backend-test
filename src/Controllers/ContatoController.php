<?php

namespace App\Controllers;

use App\Model\Contato;
use App\Model\Pessoa;
use App\Model\TipoContato;
use Doctrine\ORM\EntityNotFoundException;

class ContatoController extends BaseController
{

    private ?int $savedPersonIdBeforeDelete = null;

    public function __construct(
        private readonly ?int $personId = null,
        private readonly ?int $contactId = null,
    ) {
    }

    public function view(): array
    {
        $person = $this->findPerson();

        $contacts = $this->getEntityManager()
            ->getRepository(Contato::class)
            ->findBy(['pessoa' => $person], ['id' => 'ASC']);

        return ['person' => $person, 'contacts' => $contacts];
    }

    public function add(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            clearOld();
        }

        $contact = new Contato();
        $contact->setDescricao(old('descricao'));

        $type = old('tipo');

        if ($type !== '' && in_array((int) $type, [1, 2], true)) {
            $contact->setTipo(TipoContato::from((int) $type));
        }

        return ['contact' => $contact, 'person' => $this->findPerson()];
    }

    public function addPost(): bool
    {
        $person = $this->findPerson();
        $errors = $this->validate($_POST);

        if ($errors !== []) {
            $_SESSION['old'] = $_POST;
            flash('error', implode(' ', $errors));

            return false;
        }

        $contact = new Contato();
        $contact->setTipo(TipoContato::from((int) $_POST['tipo']));
        $contact->setDescricao(trim((string) $_POST['descricao']));
        $person->addContato($contact);

        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();

        clearOld();
        flash('success', 'Contato criado com sucesso.');

        return true;
    }

    public function edit(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            clearOld();
        }

        $contact = $this->findContact();

        return ['contact' => $contact, 'person' => $contact->getPessoa()];
    }

    public function editPost(): bool
    {
        $contact = $this->findContact();
        $errors = $this->validate($_POST);

        if ($errors !== []) {
            $_SESSION['old'] = $_POST;
            flash('error', implode(' ', $errors));

            return false;
        }

        $contact->setTipo(TipoContato::from((int) $_POST['tipo']));
        $contact->setDescricao(trim((string) $_POST['descricao']));

        $this->getEntityManager()->flush();

        clearOld();
        flash('success', 'Contato atualizado com sucesso.');

        return true;
    }

    public function delete(): bool
    {
        $contact = $this->findContact();
        $this->savedPersonIdBeforeDelete = $contact->getPessoa()->getId();

        $this->getEntityManager()->remove($contact);
        $this->getEntityManager()->flush();

        flash('success', 'Contato excluído.');

        return true;
    }

    public function contactPersonId(): ?int
    {
        if ($this->personId !== null) {
            return $this->personId;
        }

        if ($this->savedPersonIdBeforeDelete !== null) {
            return $this->savedPersonIdBeforeDelete;
        }

        return $this->findContact()->getPessoa()->getId();
    }

    private function findPerson(): Pessoa
    {
        $person = $this->personId !== null
            ? $this->getEntityManager()->find(Pessoa::class, $this->personId)
            : null;

        if ($person === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(
                Pessoa::class,
                ['id' => (string) $this->personId]
            );
        }

        return $person;
    }

    private function findContact(): Contato
    {
        $contact = $this->contactId !== null
            ? $this->getEntityManager()->find(Contato::class, $this->contactId)
            : null;

        if ($contact === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(
                Contato::class,
                ['id' => (string) $this->contactId]
            );
        }

        return $contact;
    }

    private function validate(array $data): array
    {
        $errors = [];

        $type = $data['tipo'] ?? null;

        if ($type === null || !in_array((int) $type, [1, 2], true)) {
            $errors[] = 'Tipo de contato inválido.';
        }

        $description = trim((string) ($data['descricao'] ?? ''));

        if ($description === '') {
            $errors[] = 'Descrição é obrigatória.';
        } elseif (mb_strlen($description) > 255) {
            $errors[] = 'Descrição deve ter no máximo 255 caracteres.';
        } elseif ((int) $type === TipoContato::Telefone->value) {
            $digits = preg_replace('/\D+/', '', $description) ?? '';

            if ($digits === '') {
                $errors[] = 'Telefone deve conter apenas números.';
            } elseif (strlen($digits) < 10 || strlen($digits) > 11) {
                $errors[] = 'Telefone deve ter 10 ou 11 dígitos (DDD + número).';
            }
        } elseif ((int) $type === TipoContato::Email->value && !filter_var($description, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }

        return $errors;
    }
}
