<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 */
$this->assign('title', 'Înregistrare');
$this->setLayout('admin_login');
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #1976d2;">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus"></i>
                    Înregistrare cont nou
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted text-center mb-4">
                    Creați un cont pentru a putea face programări online
                </p>

                <?= $this->Form->create($patient, [
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]) ?>

                <div class="mb-3">
                    <?= $this->Form->control('full_name', [
                        'type' => 'text',
                        'label' => [
                            'text' => 'Nume complet *',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control' . ($patient->hasErrors() && $patient->getError('full_name') ? ' is-invalid' : ''),
                        'placeholder' => 'Introduceți numele complet',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        <?= $patient->getError('full_name')['_empty'] ?? 'Vă rugăm să introduceți numele complet.' ?>
                    </div>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('email', [
                        'type' => 'email',
                        'label' => [
                            'text' => 'Adresă de email *',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control' . ($patient->hasErrors() && $patient->getError('email') ? ' is-invalid' : ''),
                        'placeholder' => 'Introduceți adresa de email',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        <?= $patient->getError('email')['_empty'] ?? $patient->getError('email')['email'] ?? 'Vă rugăm să introduceți o adresă de email validă.' ?>
                    </div>
                    <div class="form-text">
                        Veți primi un email de confirmare la această adresă.
                    </div>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('phone', [
                        'type' => 'tel',
                        'label' => [
                            'text' => 'Număr de telefon *',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control' . ($patient->hasErrors() && $patient->getError('phone') ? ' is-invalid' : ''),
                        'placeholder' => 'Introduceți numărul de telefon',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        <?= $patient->getError('phone')['_empty'] ?? 'Vă rugăm să introduceți numărul de telefon.' ?>
                    </div>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'label' => [
                            'text' => 'Parolă *',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Introduceți parola',
                        'required' => true,
                        'minlength' => 8
                    ]) ?>
                    <div class="form-text">
                        Parola trebuie să aibă minim 8 caractere.
                    </div>
                </div>

                <div class="mb-4">
                    <?= $this->Form->control('password_confirm', [
                        'type' => 'password',
                        'label' => [
                            'text' => 'Confirmă parola *',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Reintroduceți parola',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        Parolele nu se potrivesc.
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label" for="termsCheck">
                            Sunt de acord cu <?= $this->Html->link('termenii și condițiile', '#', ['target' => '_blank']) ?>
                        </label>
                        <div class="invalid-feedback">
                            Trebuie să acceptați termenii și condițiile.
                        </div>
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <?= $this->Form->button('<i class="fas fa-user-plus"></i> Înregistrare', [
                        'type' => 'submit',
                        'class' => 'btn btn-primary btn-lg',
                        'style' => 'background-color: #1976d2; border-color: #1976d2;',
                        'escapeTitle' => false
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>

                <hr class="my-3">

                <div class="text-center">
                    <p class="mb-2">Aveți deja un cont?</p>
                    <?= $this->Html->link(
                        '<i class="fas fa-sign-in-alt"></i> Autentificați-vă',
                        ['action' => 'login'],
                        [
                            'class' => 'btn btn-outline-primary',
                            'escape' => false
                        ]
                    ) ?>
                </div>

                <hr class="my-3">

                <div class="text-center">
                    <?= $this->Html->link(
                        '<i class="fas fa-home"></i> Înapoi la site',
                        '/',
                        [
                            'class' => 'btn btn-outline-secondary btn-sm',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap form validation with password matching
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var password = document.querySelector('input[name="password"]');
        var confirmPassword = document.querySelector('input[name="password_confirm"]');

        // Password matching validation
        function validatePasswordMatch() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Parolele nu se potrivesc.');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        if (password && confirmPassword) {
            password.addEventListener('change', validatePasswordMatch);
            confirmPassword.addEventListener('keyup', validatePasswordMatch);
        }

        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                validatePasswordMatch();
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
