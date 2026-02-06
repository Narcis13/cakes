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
                            <?= $this->Form->control('email2FA', [
                                'type' => 'email',
                                'label' => ['text' => 'Email pentru verificare 2FA (opțional)', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'Lasă gol pentru a dezactiva 2FA',
                                'required' => false,
                            ]) ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i>
                                Dacă este setat, la autentificare se va trimite un cod de verificare pe acest email.
                            </small>
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
                                'placeholder' => 'Introdu parola (min. 12 caractere)',
                                'required' => true,
                                'minlength' => 12,
                                'id' => 'password-field'
                            ]) ?>
                            <div class="password-requirements mt-2">
                                <small class="text-muted">Cerințe parolă:</small>
                                <ul class="list-unstyled mb-0 small">
                                    <li id="req-length" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Minim 12 caractere
                                    </li>
                                    <li id="req-uppercase" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Cel puțin o literă mare (A-Z)
                                    </li>
                                    <li id="req-lowercase" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Cel puțin o literă mică (a-z)
                                    </li>
                                    <li id="req-number" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Cel puțin o cifră (0-9)
                                    </li>
                                    <li id="req-special" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Cel puțin un caracter special (!@#$%^&amp;*(),.?":{}|&lt;&gt;)
                                    </li>
                                </ul>
                            </div>
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
// Validare formular cu verificare cerințe parolă
(function() {
    'use strict';

    // Funcții pentru validare parolă în timp real
    function validatePassword(password) {
        return {
            length: password.length >= 12,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };
    }

    function updateRequirementUI(elementId, isValid) {
        var element = document.getElementById(elementId);
        if (element) {
            if (isValid) {
                element.classList.remove('text-danger');
                element.classList.add('text-success');
                element.querySelector('i').classList.remove('fa-times-circle');
                element.querySelector('i').classList.add('fa-check-circle');
            } else {
                element.classList.remove('text-success');
                element.classList.add('text-danger');
                element.querySelector('i').classList.remove('fa-check-circle');
                element.querySelector('i').classList.add('fa-times-circle');
            }
        }
    }

    window.addEventListener('load', function() {
        var passwordField = document.getElementById('password-field');

        // Verificare în timp real a cerințelor parolei
        if (passwordField) {
            passwordField.addEventListener('input', function() {
                var validation = validatePassword(this.value);
                updateRequirementUI('req-length', validation.length);
                updateRequirementUI('req-uppercase', validation.uppercase);
                updateRequirementUI('req-lowercase', validation.lowercase);
                updateRequirementUI('req-number', validation.number);
                updateRequirementUI('req-special', validation.special);
            });
        }

        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                var password = form.querySelector('input[name="password"]').value;
                var confirmPassword = form.querySelector('input[name="confirm_password"]').value;

                // Verifică cerințele parolei
                var validation = validatePassword(password);
                var allValid = validation.length && validation.uppercase &&
                               validation.lowercase && validation.number && validation.special;

                if (!allValid) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert('Parola nu îndeplinește toate cerințele de securitate!');
                    return false;
                }

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
