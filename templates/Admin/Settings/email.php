<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, string> $emailSettings
 * @var array<string, string> $platforms
 * @var string $currentPlatform
 */
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
                ['class' => 'list-group-item list-group-item-action']
            ) ?>
        </div>

        <div class="list-group mt-3">
            <strong class="list-group-item list-group-item-action active" aria-current="true">
                <?= __('Status curent') ?>
            </strong>
            <div class="list-group-item">
                <small class="text-muted">Platformă activă:</small><br>
                <span class="badge bg-<?= $currentPlatform === 'RESEND' ? 'primary' : 'success' ?> fs-6">
                    <?= h($currentPlatform) ?>
                </span>
            </div>
        </div>
    </aside>

    <div class="col-md-9 settings form content">
        <h2><?= __('Setări Email') ?></h2>

        <?= $this->Form->create(null, ['url' => ['action' => 'email']]) ?>

        <!-- Selectare platformă -->
        <fieldset class="mb-4">
            <legend><?= __('Platformă de trimitere') ?></legend>
            <div class="mb-3">
                <?= $this->Form->control('PLATFORMA_EMAIL', [
                    'type' => 'select',
                    'options' => $platforms,
                    'value' => $emailSettings['PLATFORMA_EMAIL'],
                    'label' => ['class' => 'form-label', 'text' => 'Selectează platforma'],
                    'class' => 'form-select',
                    'id' => 'platformaEmail',
                ]) ?>
                <div class="form-text">
                    <strong>RESEND</strong> - Serviciu API pentru email (recomandat pentru producție)<br>
                    <strong>SMTP</strong> - Server SMTP tradițional
                </div>
            </div>
        </fieldset>

        <!-- Setări expeditor -->
        <fieldset class="mb-4">
            <legend><?= __('Setări expeditor') ?></legend>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('EMAIL_FROM_ADDRESS', [
                        'type' => 'email',
                        'value' => $emailSettings['EMAIL_FROM_ADDRESS'],
                        'label' => ['class' => 'form-label', 'text' => 'Adresa de email expeditor'],
                        'class' => 'form-control',
                        'placeholder' => 'noreply@example.com',
                    ]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('EMAIL_FROM_NAME', [
                        'type' => 'text',
                        'value' => $emailSettings['EMAIL_FROM_NAME'],
                        'label' => ['class' => 'form-label', 'text' => 'Numele expeditorului'],
                        'class' => 'form-control',
                        'placeholder' => 'Spitalul Militar Pitesti',
                    ]) ?>
                </div>
            </div>
        </fieldset>

        <!-- Setări SMTP -->
        <fieldset class="mb-4" id="smtpSettings">
            <legend><?= __('Configurare SMTP') ?></legend>
            <div class="alert alert-info">
                <small>Aceste setări sunt necesare doar dacă folosiți platforma SMTP.</small>
            </div>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <?= $this->Form->control('SMTP_HOST', [
                        'type' => 'text',
                        'value' => $emailSettings['SMTP_HOST'],
                        'label' => ['class' => 'form-label', 'text' => 'Server SMTP (Host)'],
                        'class' => 'form-control',
                        'placeholder' => 'smtp.example.com',
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('SMTP_PORT', [
                        'type' => 'number',
                        'value' => $emailSettings['SMTP_PORT'],
                        'label' => ['class' => 'form-label', 'text' => 'Port'],
                        'class' => 'form-control',
                        'placeholder' => '587',
                    ]) ?>
                    <div class="form-text">Porturi comune: 587 (TLS), 465 (SSL), 25, 26</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('SMTP_USER', [
                        'type' => 'text',
                        'value' => $emailSettings['SMTP_USER'],
                        'label' => ['class' => 'form-label', 'text' => 'Utilizator SMTP'],
                        'class' => 'form-control',
                        'placeholder' => 'user@example.com',
                        'autocomplete' => 'off',
                    ]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $this->Form->control('SMTP_PASSWORD', [
                        'type' => 'password',
                        'value' => $emailSettings['SMTP_PASSWORD'],
                        'label' => ['class' => 'form-label', 'text' => 'Parolă SMTP'],
                        'class' => 'form-control',
                        'autocomplete' => 'new-password',
                    ]) ?>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <?= $this->Form->checkbox('SMTP_TLS', [
                        'value' => '1',
                        'checked' => $emailSettings['SMTP_TLS'] === '1',
                        'class' => 'form-check-input',
                        'id' => 'smtpTls',
                        'hiddenField' => true,
                    ]) ?>
                    <label class="form-check-label" for="smtpTls">
                        Activează TLS (recomandat)
                    </label>
                </div>
            </div>
        </fieldset>

        <?= $this->Form->button(__('Salvează setările'), ['class' => 'btn btn-primary btn-lg']) ?>
        <?= $this->Form->end() ?>

        <!-- Test email -->
        <hr class="my-4">

        <fieldset>
            <legend><?= __('Test email') ?></legend>
            <?= $this->Form->create(null, ['url' => ['action' => 'testEmail']]) ?>
            <div class="row align-items-end">
                <div class="col-md-8 mb-3">
                    <?= $this->Form->control('test_email', [
                        'type' => 'email',
                        'label' => ['class' => 'form-label', 'text' => 'Adresa de email pentru test'],
                        'class' => 'form-control',
                        'placeholder' => 'test@example.com',
                        'required' => true,
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->button(__('Trimite email de test'), [
                        'class' => 'btn btn-outline-secondary w-100',
                    ]) ?>
                </div>
            </div>
            <div class="form-text">
                Trimite un email de test pentru a verifica configurarea curentă (<?= h($currentPlatform) ?>).
            </div>
            <?= $this->Form->end() ?>
        </fieldset>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const platformSelect = document.getElementById('platformaEmail');
    const smtpSettings = document.getElementById('smtpSettings');

    function toggleSmtpSettings() {
        if (platformSelect.value === 'SMTP') {
            smtpSettings.style.opacity = '1';
            smtpSettings.querySelectorAll('input').forEach(input => {
                input.removeAttribute('disabled');
            });
        } else {
            smtpSettings.style.opacity = '0.5';
            // Don't disable - just visual indication
        }
    }

    platformSelect.addEventListener('change', toggleSmtpSettings);
    toggleSmtpSettings();
});
</script>
