<?php
/**
 * @var \App\View\AppView $this
 * @var string $token
 */
$this->assign('title', 'Resetare parolă');
$this->setLayout('admin_login');
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #1976d2;">
                <h4 class="mb-0">
                    <i class="fas fa-lock"></i>
                    Resetare parolă
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted text-center mb-4">
                    Introduceți noua parolă pentru contul dvs.
                </p>

                <?= $this->Form->create(null, [
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]) ?>

                <div class="mb-3">
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'label' => [
                            'text' => 'Parolă nouă',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Introduceți noua parolă',
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
                            'text' => 'Confirmă parola nouă',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Reintroduceți noua parolă',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        Parolele nu se potrivesc.
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <?= $this->Form->button('<i class="fas fa-save"></i> Salvează parola nouă', [
                        'type' => 'submit',
                        'class' => 'btn btn-primary btn-lg',
                        'style' => 'background-color: #1976d2; border-color: #1976d2;',
                        'escapeTitle' => false
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>

                <hr class="my-3">

                <div class="text-center">
                    <?= $this->Html->link(
                        '<i class="fas fa-arrow-left"></i> Înapoi la autentificare',
                        ['action' => 'login'],
                        [
                            'class' => 'btn btn-outline-secondary',
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
