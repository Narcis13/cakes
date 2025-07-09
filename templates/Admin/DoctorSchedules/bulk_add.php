<?php
/**
 * @var \App\View\AppView $this
 * @var array $staff
 * @var array $services
 * @var array $daysOfWeek
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Doctor Schedules'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="doctorSchedules form content">
            <?= $this->Form->create(null, ['url' => ['action' => 'bulkAdd']]) ?>
            <fieldset>
                <legend><?= __('Bulk Add Doctor Schedules') ?></legend>
                <p class="text-muted"><?= __('Create multiple schedules at once for multiple doctors and days.') ?></p>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('staff_ids', [
                            'label' => __('Select Doctors'),
                            'options' => $staff,
                            'multiple' => true,
                            'class' => 'form-select',
                            'size' => 8,
                            'required' => true,
                            'help' => __('Hold Ctrl/Cmd to select multiple doctors')
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('days_of_week', [
                            'label' => __('Select Days'),
                            'options' => $daysOfWeek,
                            'multiple' => true,
                            'class' => 'form-select',
                            'size' => 7,
                            'required' => true,
                            'help' => __('Hold Ctrl/Cmd to select multiple days')
                        ]) ?>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('service_id', [
                            'label' => __('Service'),
                            'options' => $services,
                            'empty' => __('-- Select Service --'),
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('start_time', [
                            'label' => __('Start Time'),
                            'type' => 'time',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-6">
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
                            'default' => 1,
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
                            'default' => 0,
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
                            'checked' => true,
                            'class' => 'form-check-input'
                        ]) ?>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <strong><?= __('Note:') ?></strong> <?= __('This will create schedules for all selected combinations of doctors and days with the same time settings.') ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Create Schedules'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>