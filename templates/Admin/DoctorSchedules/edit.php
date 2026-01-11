<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DoctorSchedule $doctorSchedule
 * @var array $staff
 * @var array $services
 * @var array $daysOfWeek
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Html->link(__('Ștergere'), ['action' => 'delete', $doctorSchedule->id], ['confirm' => __('Sigur doriți să ștergeți programul #{0}?', $doctorSchedule->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Lista Program Medici'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="doctorSchedules form content">
            <?= $this->Form->create($doctorSchedule) ?>
            <fieldset>
                <legend><?= __('Editare Program Medic') ?></legend>
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('staff_id', [
                            'label' => __('Medic'),
                            'options' => $staff,
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('service_id', [
                            'label' => __('Serviciu'),
                            'options' => $services,
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <?= $this->Form->control('day_of_week', [
                            'label' => __('Zi din săptămână'),
                            'options' => $daysOfWeek,
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('start_time', [
                            'label' => __('Ora început'),
                            'type' => 'time',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('end_time', [
                            'label' => __('Ora sfârșit'),
                            'type' => 'time',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <?= $this->Form->control('max_appointments', [
                            'label' => __('Număr maxim programări'),
                            'type' => 'number',
                            'min' => 1,
                            'class' => 'form-control',
                            'help' => __('Numărul maxim de programări permise în acest interval orar')
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('slot_duration', [
                            'label' => __('Durată interval (minute)'),
                            'type' => 'number',
                            'min' => 5,
                            'step' => 5,
                            'class' => 'form-control',
                            'help' => __('Lăsați gol pentru a folosi durata implicită a serviciului')
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('buffer_minutes', [
                            'label' => __('Minute tampon'),
                            'type' => 'number',
                            'min' => 0,
                            'class' => 'form-control',
                            'help' => __('Minute între programări')
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('is_active', [
                            'label' => __('Activ'),
                            'type' => 'checkbox',
                            'class' => 'form-check-input'
                        ]) ?>
                    </div>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Salvează'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Anulează'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
