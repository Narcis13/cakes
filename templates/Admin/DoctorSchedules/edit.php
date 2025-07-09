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
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Delete'), ['action' => 'delete', $doctorSchedule->id], ['confirm' => __('Are you sure you want to delete # {0}?', $doctorSchedule->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Doctor Schedules'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="doctorSchedules form content">
            <?= $this->Form->create($doctorSchedule) ?>
            <fieldset>
                <legend><?= __('Edit Doctor Schedule') ?></legend>
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('staff_id', [
                            'label' => __('Doctor'),
                            'options' => $staff,
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('service_id', [
                            'label' => __('Service'),
                            'options' => $services,
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <?= $this->Form->control('day_of_week', [
                            'label' => __('Day of Week'),
                            'options' => $daysOfWeek,
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('start_time', [
                            'label' => __('Start Time'),
                            'type' => 'time',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('end_time', [
                            'label' => __('End Time'),
                            'type' => 'time',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <?= $this->Form->control('max_appointments', [
                            'label' => __('Maximum Appointments'),
                            'type' => 'number',
                            'min' => 1,
                            'class' => 'form-control',
                            'help' => __('Maximum number of appointments allowed in this time slot')
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('slot_duration', [
                            'label' => __('Slot Duration (minutes)'),
                            'type' => 'number',
                            'min' => 5,
                            'step' => 5,
                            'class' => 'form-control',
                            'help' => __('Leave empty to use service default duration')
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('buffer_minutes', [
                            'label' => __('Buffer Minutes'),
                            'type' => 'number',
                            'min' => 0,
                            'class' => 'form-control',
                            'help' => __('Minutes between appointments')
                        ]) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('is_active', [
                            'label' => __('Active'),
                            'type' => 'checkbox',
                            'class' => 'form-check-input'
                        ]) ?>
                    </div>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>