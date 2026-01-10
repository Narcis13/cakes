<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StaffUnavailability $staffUnavailability
 * @var string[]|\Cake\Collection\CollectionInterface $staff
 */
$this->assign('title', 'Editare Indisponibilitate Personal');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-edit"></i>
                Editare Indisponibilitate Personal
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la Listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>

        <div class="card">
            <div class="card-body">
                <?= $this->Form->create($staffUnavailability, ['class' => 'needs-validation', 'novalidate' => true]) ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <?= $this->Form->control('staff_id', [
                                'type' => 'select',
                                'options' => $staff,
                                'label' => ['text' => 'Membru Personal', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('date_from', [
                                'type' => 'date',
                                'label' => ['text' => 'De la data', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'value' => $staffUnavailability->date_from ? $staffUnavailability->date_from->format('Y-m-d') : ''
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('date_to', [
                                'type' => 'date',
                                'label' => ['text' => 'Până la data', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'value' => $staffUnavailability->date_to ? $staffUnavailability->date_to->format('Y-m-d') : ''
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <?= $this->Form->control('reason', [
                                'type' => 'text',
                                'label' => ['text' => 'Motiv (Opțional)', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'ex: Concediu, Concediu medical, Conferință...',
                                'maxlength' => 255
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Perioada curentă:</strong>
                    <?= $staffUnavailability->date_from->format('d.m.Y') ?> până la
                    <?= $staffUnavailability->date_to->format('d.m.Y') ?>
                    (<?php
                        $days = $staffUnavailability->date_from->diffInDays($staffUnavailability->date_to) + 1;
                        echo $days . ' ' . ($days === 1 ? 'zi' : 'zile');
                    ?>)
                </div>

                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Actualizează Indisponibilitatea',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Anulează',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary']
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Ștergere',
                        ['action' => 'delete', $staffUnavailability->id],
                        [
                            'class' => 'btn btn-danger ms-auto',
                            'confirm' => 'Sigur doriți să ștergeți această perioadă de indisponibilitate?',
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
// Form validation and date validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var dateFrom = document.getElementById('date-from');
        var dateTo = document.getElementById('date-to');

        // Update minimum date for date_to when date_from changes
        if (dateFrom && dateTo) {
            dateFrom.addEventListener('change', function() {
                dateTo.setAttribute('min', this.value);
                if (dateTo.value && dateTo.value < this.value) {
                    dateTo.value = this.value;
                }
            });
        }

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
