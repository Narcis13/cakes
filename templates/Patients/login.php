<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Autentificare');
$this->setLayout('admin_login');
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #1976d2;">
                <h4 class="mb-0">
                    <i class="fas fa-hospital-user"></i>
                    Portal Pacient
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted text-center mb-4">
                    Autentificați-vă pentru a accesa portalul pacientului
                </p>

                <?= $this->Form->create(null, [
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]) ?>

                <div class="mb-3">
                    <?= $this->Form->control('email', [
                        'type' => 'email',
                        'label' => [
                            'text' => 'Adresă de email',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Introduceți adresa de email',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        Vă rugăm să introduceți o adresă de email validă.
                    </div>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'label' => [
                            'text' => 'Parolă',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Introduceți parola',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        Vă rugăm să introduceți parola.
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <?= $this->Form->button('<i class="fas fa-sign-in-alt"></i> Autentificare', [
                        'type' => 'submit',
                        'class' => 'btn btn-primary btn-lg',
                        'style' => 'background-color: #1976d2; border-color: #1976d2;',
                        'escapeTitle' => false
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>

                <div class="text-center mb-3">
                    <?= $this->Html->link(
                        'Ați uitat parola?',
                        ['action' => 'forgotPassword'],
                        ['class' => 'text-decoration-none']
                    ) ?>
                </div>

                <hr class="my-3">

                <div class="text-center">
                    <p class="mb-2">Nu aveți cont?</p>
                    <?= $this->Html->link(
                        '<i class="fas fa-user-plus"></i> Înregistrați-vă',
                        ['action' => 'register'],
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
// Bootstrap form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
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
