<?php
/**
 * @var \App\Model\Contato $contact
 * @var \App\Model\Pessoa $person
 */

use App\Model\TipoContato;

$isEdit = $contact->getId() !== null;
$selectedType = (string) old('tipo', '');

if ($selectedType === '' && $isEdit) {
    $selectedType = (string) $contact->getTipo()->value;
}
?>
<h1 class="h4 mb-3"><?= $isEdit ? 'Editar Contato' : 'Novo Contato' ?></h1>

<div class="card">
    <div class="card-body">
        <p class="text-muted mb-4">
            Pessoa: <strong><?= escape($person->getNome()) ?></strong>
        </p>

        <form method="post" action="/contatos?action=<?= $isEdit ? 'edit' : 'add' ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int) $contact->getId() ?>">
            <?php else: ?>
                <input type="hidden" name="pessoa_id" value="<?= (int) $person->getId() ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo *</label>
                <select class="form-select" id="tipo" name="tipo" required onchange="trocarTipo()">
                    <option value="">Selecione...</option>
                    <?php foreach (TipoContato::cases() as $case): ?>
                        <option value="<?= $case->value ?>"
                            <?php if ($selectedType === (string) $case->value) echo 'selected'; ?>>
                            <?= escape($case->label()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição *</label>
                <input type="text" class="form-control" id="descricao" name="descricao" maxlength="255" required
                       value="<?= escape(old('descricao', $contact->getDescricao())) ?>">
            </div>

            <div class="d-flex gap-3 mt-4">
                <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Salvar' : 'Cadastrar' ?></button>
                <a class="btn btn-secondary" href="/contatos?pessoa_id=<?= (int) $person->getId() ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
function trocarTipo() {
    var typeSelect = document.getElementById('tipo');
    var field = document.getElementById('descricao');

    if (typeSelect.value === '1') {
        field.setAttribute('type', 'text');
        field.setAttribute('pattern', '[0-9]{8,20}');
        field.setAttribute('inputmode', 'numeric');
        field.setAttribute('placeholder', '11999999999');
    } else if (typeSelect.value === '2') {
        field.setAttribute('type', 'email');
        field.removeAttribute('pattern');
        field.removeAttribute('inputmode');
        field.setAttribute('placeholder', 'exemplo@dominio.com');
    } else {
        field.setAttribute('type', 'text');
        field.removeAttribute('pattern');
        field.removeAttribute('inputmode');
        field.setAttribute('placeholder', '');
    }
}
trocarTipo();
</script>
