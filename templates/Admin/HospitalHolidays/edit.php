<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\HospitalHoliday $hospitalHoliday
 */
$this->assign('title', 'Editare Sărbătoare Spital');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-edit"></i>
                Editare Sărbătoare Spital
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la Listă',
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
                                'label' => ['text' => 'Nume Sărbătoare', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'ex: Crăciun, Revelion',
                                'required' => true,
                                'maxlength' => 100
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('date', [
                                'type' => 'date',
                                'label' => ['text' => 'Data', 'class' => 'form-label'],
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
                                    'label' => 'Recurent anual (aceeași dată în fiecare an)',
                                    'class' => 'form-check-input',
                                    'templateVars' => ['labelClass' => 'form-check-label']
                                ]) ?>
                            </div>
                            <small class="text-muted">
                                Bifați aceasta dacă sărbătoarea are loc în aceeași dată în fiecare an (ex: Crăciun, Revelion)
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <?= $this->Form->control('description', [
                                'type' => 'textarea',
                                'label' => ['text' => 'Descriere (Opțional)', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'rows' => 3,
                                'placeholder' => 'Informații suplimentare despre această sărbătoare...'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Sărbătoare curentă:</strong> <?= h($hospitalHoliday->name) ?> pe <?= $hospitalHoliday->date->format('d.m.Y') ?>
                    <?php if ($hospitalHoliday->is_recurring): ?>
                        <span class="badge bg-info ms-2">Recurent Anual</span>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Actualizează Sărbătoarea',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escapeTitle' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Anulează',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary']
                    ) ?>
                </div>

                <?= $this->Form->end() ?>

                <div class="mt-3">
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Ștergere',
                        ['action' => 'delete', $hospitalHoliday->id],
                        [
                            'class' => 'btn btn-danger',
                            'confirm' => 'Sigur doriți să ștergeți această sărbătoare?',
                            'escape' => false
                        ]
                    ) ?>
                </div>
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
