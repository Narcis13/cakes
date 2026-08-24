<?php
/**
 * @var \App\View\AppView $this
 * @var bool $maintenanceEnabled
 * @var string $maintenanceTitle
 * @var string $maintenanceMessage
 * @var string $maintenanceEta
 */
$this->assign('title', 'Mentenanță portal');
?>
<div class="row">
    <aside class="col-md-3">
        <div class="list-group">
            <strong class="list-group-item list-group-item-action active" aria-current="true">
                <?= __('Navigare') ?>
            </strong>
            <?= $this->Html->link(
                __('Setări generale'),
                ['action' => 'index'],
                ['class' => 'list-group-item list-group-item-action'],
            ) ?>
            <?= $this->Html->link(
                __('Setări email'),
                ['action' => 'email'],
                ['class' => 'list-group-item list-group-item-action'],
            ) ?>
        </div>

        <div class="list-group mt-3">
            <strong class="list-group-item list-group-item-action active" aria-current="true">
                <?= __('Status curent') ?>
            </strong>
            <div class="list-group-item">
                <small class="text-muted">Portal programări online:</small><br>
                <span class="badge bg-<?= $maintenanceEnabled ? 'danger' : 'success' ?> fs-6">
                    <?= $maintenanceEnabled ? __('MENTENANȚĂ') : __('FUNCȚIONAL') ?>
                </span>
            </div>
            <div class="list-group-item">
                <?= $this->Form->postLink(
                    $maintenanceEnabled
                        ? '<i class="fas fa-play"></i> ' . __('Revino la modul normal')
                        : '<i class="fas fa-tools"></i> ' . __('Trece în mentenanță'),
                    ['action' => 'togglePortalMaintenance'],
                    [
                        'class' => 'btn btn-sm w-100 btn-' . ($maintenanceEnabled ? 'success' : 'danger'),
                        'escape' => false,
                        'confirm' => $maintenanceEnabled
                            ? __('Confirmi redeschiderea portalului pentru pacienți?')
                            : __('Confirmi închiderea portalului? Pacienții nu vor mai putea face programări online.'),
                    ],
                ) ?>
            </div>
        </div>
    </aside>

    <div class="col-md-9 settings form content">
        <h2><?= __('Mentenanță portal pacienți') ?></h2>
        <p class="text-muted">
            <?= __('Cât timp modul mentenanță este activ, paginile portalului (autentificare, cont pacient '
                . 'și programări online) afișează mesajul de mai jos în locul conținutului obișnuit. '
                . 'Utilizatorii autentificați în panoul de administrare pot accesa în continuare portalul '
                . 'pentru verificări.') ?>
        </p>

        <?php if ($maintenanceEnabled) : ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <strong><?= __('Portalul este în mentenanță.') ?></strong>
                <?= __('Pacienții nu pot face programări online în acest moment.') ?>
            </div>
        <?php else : ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i>
                <strong><?= __('Portalul funcționează normal.') ?></strong>
                <?= __('Pacienții pot face programări online.') ?>
            </div>
        <?php endif; ?>

        <?= $this->Form->create(null, ['url' => ['action' => 'portalMaintenance']]) ?>

        <fieldset class="mb-4">
            <legend><?= __('Stare portal') ?></legend>
            <div class="form-check form-switch mb-3">
                <?= $this->Form->checkbox('maintenance_mode', [
                    'checked' => $maintenanceEnabled,
                    'class' => 'form-check-input',
                    'id' => 'maintenanceMode',
                    'role' => 'switch',
                ]) ?>
                <label class="form-check-label" for="maintenanceMode">
                    <strong><?= __('Activează modul MENTENANȚĂ pentru portalul de programări') ?></strong>
                </label>
                <div class="form-text">
                    <?= __('Debifat = portalul funcționează normal.') ?>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-4">
            <legend><?= __('Mesaj afișat pacienților') ?></legend>
            <div class="mb-3">
                <?= $this->Form->control('maintenance_title', [
                    'type' => 'text',
                    'value' => $maintenanceTitle,
                    'label' => ['class' => 'form-label', 'text' => __('Titlu')],
                    'class' => 'form-control',
                    'maxlength' => 255,
                ]) ?>
            </div>
            <div class="mb-3">
                <?= $this->Form->control('maintenance_message', [
                    'type' => 'textarea',
                    'value' => $maintenanceMessage,
                    'label' => ['class' => 'form-label', 'text' => __('Mesaj')],
                    'class' => 'form-control',
                    'rows' => 5,
                ]) ?>
                <div class="form-text">
                    <?= __('Textul explicativ afișat pacienților care accesează portalul.') ?>
                </div>
            </div>
            <div class="mb-3">
                <?= $this->Form->control('maintenance_eta', [
                    'type' => 'text',
                    'value' => $maintenanceEta,
                    'label' => ['class' => 'form-label', 'text' => __('Estimare revenire (opțional)')],
                    'class' => 'form-control',
                    'placeholder' => __('ex: 24.08.2026, ora 18:00'),
                    'maxlength' => 255,
                ]) ?>
            </div>
        </fieldset>

        <div class="d-flex gap-2">
            <?= $this->Form->button(__('Salvează setările'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Renunță'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>
