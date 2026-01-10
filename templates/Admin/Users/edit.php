<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Editează utilizator');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-edit"></i>
                Editează utilizator
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
                                'required' => true
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('password', [
                                'type' => 'password',
                                'label' => ['text' => 'Parolă nouă (lasă gol pentru a păstra cea actuală)', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'Introdu parola nouă (min. 6 caractere)',
                                'required' => false,
                                'minlength' => 6,
                                'value' => ''
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('confirm_password', [
                                'type' => 'password',
                                'label' => ['text' => 'Confirmă parola nouă', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'Confirmă parola nouă',
                                'required' => false,
                                'value' => ''
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informații utilizator:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Creat:</strong> <?= $user->created ? $user->created->format('d F Y, H:i') : 'N/A' ?></li>
                            <li><strong>Ultima modificare:</strong> <?= $user->modified ? $user->modified->format('d F Y, H:i') : 'N/A' ?></li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Actualizează utilizator',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escapeTitle' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Anulează',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary']
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Șterge',
                        ['action' => 'delete', $user->id],
                        [
                            'class' => 'btn btn-danger ms-auto',
                            'confirm' => 'Ești sigur că vrei să ștergi acest utilizator?',
                            'escape' => false
                        ]
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

                // Validează parolele doar dacă se introduce o parolă nouă
                if (password && password !== confirmPassword) {
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
