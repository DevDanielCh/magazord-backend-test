<?php
/** @var \App\Model\Pessoa $person */
$isEdit = $person->getId() !== null;
?>
<h1 class="h4 mb-3"><?= $isEdit ? 'Editar Pessoa' : 'Nova Pessoa' ?></h1>

<div class="card">
    <div class="card-body">
        <form method="post" action="/pessoas?action=<?= $isEdit ? 'edit' : 'add' ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int) $person->getId() ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome *</label>
                <input type="text" class="form-control" id="nome" name="nome" maxlength="255" required
                       value="<?= escape(old('nome', $person->getNome())) ?>">
            </div>

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF * (só dígitos ou formatado)</label>
                <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" required
                       value="<?= escape(old('cpf', formatCpf($person->getCpf()))) ?>">
            </div>

            <div class="d-flex gap-3 mt-4">
                <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Salvar' : 'Cadastrar' ?></button>
                <a class="btn btn-secondary" href="/pessoas">Cancelar</a>
            </div>
        </form>
    </div>
</div>
