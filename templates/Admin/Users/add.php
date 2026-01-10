<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Adaugă utilizator');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-plus"></i>
                Adaugă utilizator nou
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la utilizatori',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>

        <div class="card">
            <div class="card-body">
                <?= $this->Form->create($user, ['class' => 'needs-validation', 'novalidate' => true]) ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('email', [
                                'type' => 'email',
                                'label' => ['text' => 'Adresă email', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'utilizator@exemplu.com',
                                'required' => true
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('role', [
                                'type' => 'select',
                                'options' => [
                                    'admin' => 'Administrator',
                                    'staff' => 'Membru personal'
                                ],
                                'label' => ['text' => 'Rol', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'empty' => '-- Selectează rol --'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('password', [
                                'type' => 'password',
                                'label' => ['text' => 'Parolă', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'Introdu parola (min. 6 caractere)',
                                'required' => true,
                                'minlength' => 6
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('confirm_password', [
                                'type' => 'password',
                                'label' => ['text' => 'Confirmă parola', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'Confirmă parola',
                                'required' => true
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informații despre roluri:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Administrator:</strong> Acces complet la toate funcționalitățile admin</li>
                            <li><strong>Membru personal:</strong> Acces limitat la gestionarea conținutului</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Salvează utilizator',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escapeTitle' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Anulează',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary']
                    ) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>
// Validare formular
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                var password = form.querySelector('input[name="password"]').value;
                var confirmPassword = form.querySelector('input[name="confirm_password"]').value;

                if (password !== confirmPassword) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert('Parolele nu se potrivesc!');
                    return false;
                }

                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
