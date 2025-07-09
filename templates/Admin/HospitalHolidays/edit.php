<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\HospitalHoliday $hospitalHoliday
 */
$this->assign('title', 'Edit Hospital Holiday');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-edit"></i>
                Edit Hospital Holiday
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>

        <div class="card">
            <div class="card-body">
                <?= $this->Form->create($hospitalHoliday, ['class' => 'needs-validation', 'novalidate' => true]) ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('name', [
                                'label' => ['text' => 'Holiday Name', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'e.g., Christmas Day, New Year\'s Day',
                                'required' => true,
                                'maxlength' => 100
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('date', [
                                'type' => 'date',
                                'label' => ['text' => 'Date', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'value' => $hospitalHoliday->date ? $hospitalHoliday->date->format('Y-m-d') : ''
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="form-check">
                                <?= $this->Form->control('is_recurring', [
                                    'type' => 'checkbox',
                                    'label' => 'Recurring annually (same date every year)',
                                    'class' => 'form-check-input',
                                    'templateVars' => ['labelClass' => 'form-check-label']
                                ]) ?>
                            </div>
                            <small class="text-muted">
                                Check this if the holiday occurs on the same date every year (e.g., Christmas, New Year's Day)
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <?= $this->Form->control('description', [
                                'type' => 'textarea',
                                'label' => ['text' => 'Description (Optional)', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'rows' => 3,
                                'placeholder' => 'Additional information about this holiday...'
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Current Holiday:</strong> <?= h($hospitalHoliday->name) ?> on <?= $hospitalHoliday->date->format('F j, Y') ?>
                    <?php if ($hospitalHoliday->is_recurring): ?>
                        <span class="badge bg-info ms-2">Recurring Annually</span>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Update Holiday',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Cancel',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary']
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Delete',
                        ['action' => 'delete', $hospitalHoliday->id],
                        [
                            'class' => 'btn btn-danger ms-auto',
                            'confirm' => 'Are you sure you want to delete this holiday?',
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
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        
        Array.prototype.filter.call(forms, function(form) {
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