<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 */
$this->assign('title', 'Profilul meu');
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-0">
            <i class="fas fa-user-edit"></i>
            Profilul meu
        </h1>
    </div>
</div>

<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user"></i>
                    Informații personale
                </h5>
            </div>
            <div class="card-body">
                <?= $this->Form->create($patient, [
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]) ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <?= $this->Form->control('full_name', [
                            'type' => 'text',
                            'label' => [
                                'text' => 'Nume complet',
                                'class' => 'form-label'
                            ],
                            'class' => 'form-control' . ($patient->hasErrors() && $patient->getError('full_name') ? ' is-invalid' : ''),
                            'required' => true
                        ]) ?>
                        <div class="invalid-feedback">
                            Vă rugăm să introduceți numele complet.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('phone', [
                            'type' => 'tel',
                            'label' => [
                                'text' => 'Număr de telefon',
                                'class' => 'form-label'
                            ],
                            'class' => 'form-control' . ($patient->hasErrors() && $patient->getError('phone') ? ' is-invalid' : ''),
                            'required' => true
                        ]) ?>
                        <div class="invalid-feedback">
                            Vă rugăm să introduceți numărul de telefon.
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Adresă de email</label>
                    <input type="email" class="form-control" value="<?= h($patient->email) ?>" disabled readonly>
                    <div class="form-text">
                        Adresa de email nu poate fi modificată. Contactați-ne dacă doriți să o schimbați.
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <?= $this->Form->button('<i class="fas fa-save"></i> Salvează modificările', [
                        'type' => 'submit',
                        'class' => 'btn btn-portal',
                        'escapeTitle' => false
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lock"></i>
                    Schimbare parolă
                </h5>
            </div>
            <div class="card-body">
                <?= $this->Form->create(null, [
                    'class' => 'needs-validation',
                    'novalidate' => true,
                    'id' => 'password-form'
                ]) ?>

                <div class="mb-3">
                    <?= $this->Form->control('current_password', [
                        'type' => 'password',
                        'label' => [
                            'text' => 'Parola curentă',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Introduceți parola curentă'
                    ]) ?>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <?= $this->Form->control('new_password', [
                            'type' => 'password',
                            'label' => [
                                'text' => 'Parola nouă',
                                'class' => 'form-label'
                            ],
                            'class' => 'form-control',
                            'placeholder' => 'Introduceți noua parolă',
                            'minlength' => 8
                        ]) ?>
                        <div class="form-text">
                            Minim 8 caractere.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('new_password_confirm', [
                            'type' => 'password',
                            'label' => [
                                'text' => 'Confirmă parola nouă',
                                'class' => 'form-label'
                            ],
                            'class' => 'form-control',
                            'placeholder' => 'Reintroduceți noua parolă'
                        ]) ?>
                        <div class="invalid-feedback">
                            Parolele nu se potrivesc.
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <?= $this->Form->button('<i class="fas fa-key"></i> Schimbă parola', [
                        'type' => 'submit',
                        'class' => 'btn btn-warning',
                        'escapeTitle' => false
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <!-- Account Info Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle"></i>
                    Informații cont
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Cont creat</small>
                    <p class="mb-0">
                        <i class="fas fa-calendar-alt text-muted"></i>
                        <?= $patient->created->format('d.m.Y H:i') ?>
                    </p>
                </div>

                <?php if ($patient->email_verified_at): ?>
                <div class="mb-3">
                    <small class="text-muted">Email verificat</small>
                    <p class="mb-0">
                        <i class="fas fa-check-circle text-success"></i>
                        <?= $patient->email_verified_at->format('d.m.Y H:i') ?>
                    </p>
                </div>
                <?php endif; ?>

                <?php if ($patient->last_login_at): ?>
                <div class="mb-3">
                    <small class="text-muted">Ultima autentificare</small>
                    <p class="mb-0">
                        <i class="fas fa-sign-in-alt text-muted"></i>
                        <?= $patient->last_login_at->format('d.m.Y H:i') ?>
                    </p>
                </div>
                <?php endif; ?>

                <hr>

                <div class="mb-3">
                    <span class="badge bg-<?= $patient->is_active ? 'success' : 'danger' ?>">
                        <?= $patient->is_active ? 'Cont activ' : 'Cont inactiv' ?>
                    </span>
                </div>

                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i>
                    <small>
                        Pentru asistență sau modificări suplimentare la cont, vă rugăm să ne contactați.
                    </small>
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

        // Password form validation
        var passwordForm = document.getElementById('password-form');
        var newPassword = document.querySelector('input[name="new_password"]');
        var confirmPassword = document.querySelector('input[name="new_password_confirm"]');

        function validatePasswordMatch() {
            if (newPassword && confirmPassword) {
                if (newPassword.value && newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Parolele nu se potrivesc.');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
        }

        if (newPassword && confirmPassword) {
            newPassword.addEventListener('change', validatePasswordMatch);
            confirmPassword.addEventListener('keyup', validatePasswordMatch);
        }

        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form === passwordForm) {
                    validatePasswordMatch();
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
