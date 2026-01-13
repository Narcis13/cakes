<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Recuperare parolă');
$this->setLayout('admin_login');
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #1976d2;">
                <h4 class="mb-0">
                    <i class="fas fa-key"></i>
                    Recuperare parolă
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted text-center mb-4">
                    Introduceți adresa de email asociată contului dvs. și vă vom trimite un link pentru resetarea parolei.
                </p>

                <?= $this->Form->create(null, [
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]) ?>

                <div class="mb-4">
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

                <div class="d-grid mb-3">
                    <?= $this->Form->button('<i class="fas fa-paper-plane"></i> Trimite link de resetare', [
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
