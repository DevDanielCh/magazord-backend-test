<?php
/** @var string $query */
/** @var array $people */
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <form class="d-flex gap-2" method="get" action="/pessoas">
        <input type="text" class="form-control" style="max-width: 260px;" id="busca" name="q"
               value="<?= escape($query) ?>" placeholder="Buscar por nome" autocomplete="off">
        <button class="btn btn-primary" type="submit">Buscar</button>
        <?php if ($query !== ''): ?>
            <a class="btn btn-secondary" href="/pessoas">Limpar</a>
        <?php endif; ?>
    </form>
    <a class="btn btn-primary" href="/pessoas?action=add">Nova Pessoa</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($people === []): ?>
            <p class="text-muted text-center py-3 mb-0">Nenhuma pessoa encontrada.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Contatos</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($people as $person): ?>
                        <tr>
                            <td><?= (int) $person->getId() ?></td>
                            <td><?= escape($person->getNome()) ?></td>
                            <td><?= escape(formatCpf($person->getCpf())) ?></td>
                            <td>
                                <a href="/pessoas?action=view&id=<?= (int) $person->getId() ?>">
                                    <?= count($person->getContatos()) ?> contato(s)
                                </a>
                            </td>
                            <td>
                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                    <a href="/pessoas?action=view&id=<?= (int) $person->getId() ?>">Contatos</a>
                                    <a href="/pessoas?action=edit&id=<?= (int) $person->getId() ?>">Editar</a>
                                    <form class="d-inline" method="post" action="/pessoas?action=delete"
                                          onsubmit="return confirm('Excluir a pessoa e todos os seus contatos?');">
                                        <input type="hidden" name="id" value="<?= (int) $person->getId() ?>">
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

<script>
(function () {
    var field = document.getElementById('busca');
    if (!field) return;

    var timer = null;

    field.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            field.form.submit();
        }, 400);
    });

    field.focus();
    field.setSelectionRange(field.value.length, field.value.length);
})();
</script>
