<?php
$this->assign('title', 'Verificare în doi pași');
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-shield-alt"></i>
                    Verificare în doi pași
                </h4>
            </div>
            <div class="card-body">
                <?= $this->Flash->render() ?>

                <p class="text-center text-muted mb-4">
                    Un cod de verificare a fost trimis la<br>
                    <strong><?= h($maskedEmail) ?></strong>
                </p>

                <?= $this->Form->create(null, [
                    'class' => 'needs-validation',
                    'novalidate' => true,
                ]) ?>

                <div class="mb-3">
                    <?= $this->Form->control('code', [
                        'type' => 'text',
                        'label' => [
                            'text' => 'Cod de verificare',
                            'class' => 'form-label',
                        ],
                        'class' => 'form-control form-control-lg text-center',
                        'placeholder' => '000000',
                        'required' => true,
                        'maxlength' => 6,
                        'pattern' => '[0-9]{6}',
                        'autocomplete' => 'one-time-code',
                        'autofocus' => true,
                        'inputmode' => 'numeric',
                        'style' => 'letter-spacing: 8px; font-size: 1.5rem; font-weight: bold;',
                    ]) ?>
                    <div class="invalid-feedback">
                        Vă rugăm să introduceți codul de 6 cifre.
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <?= $this->Form->button('Verifică', [
                        'type' => 'submit',
                        'class' => 'btn btn-primary',
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center">
                    <?= $this->Form->create(null, [
                        'url' => ['action' => 'resend2fa'],
                    ]) ?>
                        <?= $this->Form->button(
                            '<i class="fas fa-redo"></i> Retrimite codul',
                            [
                                'type' => 'submit',
                                'class' => 'btn btn-outline-secondary btn-sm',
                                'escapeTitle' => false,
                            ]
                        ) ?>
                    <?= $this->Form->end() ?>

                    <?= $this->Html->link(
                        '<i class="fas fa-arrow-left"></i> Înapoi',
                        ['action' => 'login'],
                        [
                            'class' => 'btn btn-outline-secondary btn-sm',
                            'escape' => false,
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    // Auto-focus and numeric-only input
    var codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
        });
    }

    // Bootstrap form validation
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
