<?php
/** @var \App\Model\Pessoa $person */
/** @var array $contacts */
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h1 class="h4 mb-0">Contatos de <?= escape($person->getNome()) ?></h1>
    <div class="d-flex gap-2">
        <a class="btn btn-primary" href="/contatos?action=add&pessoa_id=<?= (int) $person->getId() ?>">Novo Contato</a>
        <a class="btn btn-secondary" href="/pessoas">Voltar</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($contacts === []): ?>
            <p class="text-muted text-center py-3 mb-0">Nenhum contato cadastrado para esta pessoa.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?= (int) $contact->getId() ?></td>
                            <td><?= escape($contact->getTipo()->label()) ?></td>
                            <td><?= escape($contact->getDescricao()) ?></td>
                            <td>
                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                    <a href="/contatos?action=edit&id=<?= (int) $contact->getId() ?>">Editar</a>
                                    <form class="d-inline" method="post" action="/contatos?action=delete"
                                          onsubmit="return confirm('Excluir este contato?');">
                                        <input type="hidden" name="id" value="<?= (int) $contact->getId() ?>">
                                        <button class="btn btn-danger btn-sm" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
