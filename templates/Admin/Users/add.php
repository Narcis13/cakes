<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Add User');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-plus"></i>
                Add New User
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to Users',
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
                                'label' => ['text' => 'Email Address', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'user@example.com',
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
                                    'staff' => 'Staff Member'
                                ],
                                'label' => ['text' => 'Role', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'empty' => '-- Select Role --'
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('password', [
                                'type' => 'password',
                                'label' => ['text' => 'Password', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'Enter password (min. 6 characters)',
                                'required' => true,
                                'minlength' => 6
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('confirm_password', [
                                'type' => 'password',
                                'label' => ['text' => 'Confirm Password', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'Confirm password',
                                'required' => true
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Role Information:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Administrator:</strong> Full access to all admin features</li>
                            <li><strong>Staff Member:</strong> Limited access to content management</li>
                        </ul>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Save User',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Cancel',
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
// Form validation
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
                    alert('Passwords do not match!');
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
