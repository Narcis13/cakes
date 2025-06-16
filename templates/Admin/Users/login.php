<?php
$this->assign('title', 'Admin Login');
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-shield"></i>
                    Admin Login
                </h4>
            </div>
            <div class="card-body">
                <?= $this->Flash->render() ?>
                
                <?= $this->Form->create(null, [
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]) ?>
                
                <div class="mb-3">
                    <?= $this->Form->control('email', [
                        'type' => 'email',
                        'label' => [
                            'text' => 'Email Address',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Enter your email',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        Please provide a valid email.
                    </div>
                </div>
                
                <div class="mb-3">
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'label' => [
                            'text' => 'Password',
                            'class' => 'form-label'
                        ],
                        'class' => 'form-control',
                        'placeholder' => 'Enter your password',
                        'required' => true
                    ]) ?>
                    <div class="invalid-feedback">
                        Please provide a password.
                    </div>
                </div>
                
                <div class="d-grid">
                    <?= $this->Form->button('Login', [
                        'type' => 'submit',
                        'class' => 'btn btn-primary'
                    ]) ?>
                </div>
                
                <?= $this->Form->end() ?>
                
                <hr class="my-3">
                
                <div class="text-center">
                    <?= $this->Html->link(
                        '<i class="fas fa-home"></i> Back to Main Site',
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
