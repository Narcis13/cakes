<?php
/**
 * @var \App\View\AppView $this
 * @var array $staff
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
            <?= $this->Form->create(null, ['url' => ['action' => 'copySchedule']]) ?>
            <fieldset>
                <legend><?= __('Copy Doctor Schedule') ?></legend>
                <p class="text-muted"><?= __('Copy all active schedules from one doctor to other doctors.') ?></p>
                
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('source_staff_id', [
                            'label' => __('Copy From (Source Doctor)'),
                            'options' => $staff,
                            'empty' => __('-- Select Source Doctor --'),
                            'class' => 'form-select',
                            'required' => true,
                            'help' => __('Select the doctor whose schedule you want to copy')
                        ]) ?>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <?= $this->Form->control('target_staff_ids', [
                            'label' => __('Copy To (Target Doctors)'),
                            'options' => $staff,
                            'multiple' => true,
                            'class' => 'form-select',
                            'size' => 8,
                            'required' => true,
                            'help' => __('Select one or more doctors to copy the schedule to. Hold Ctrl/Cmd to select multiple.')
                        ]) ?>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <?= $this->Form->control('adjust_minutes', [
                            'label' => __('Time Adjustment (minutes)'),
                            'type' => 'number',
                            'default' => 0,
                            'class' => 'form-control',
                            'help' => __('Adjust all times by this many minutes. Use negative values to shift earlier, positive to shift later. Leave as 0 for no adjustment.')
                        ]) ?>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <strong><?= __('Important:') ?></strong>
                    <ul class="mb-0">
                        <li><?= __('Only active schedules will be copied.') ?></li>
                        <li><?= __('If a schedule conflicts with an existing schedule for the target doctor, it will be skipped.') ?></li>
                        <li><?= __('All copied schedules will be set as active.') ?></li>
                    </ul>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Copy Schedules'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Disable source doctor in target list when selected
    const sourceSelect = document.getElementById('source-staff-id');
    const targetSelect = document.getElementById('target-staff-ids');
    
    if (sourceSelect && targetSelect) {
        sourceSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Enable all options first
            Array.from(targetSelect.options).forEach(option => {
                option.disabled = false;
            });
            
            // Disable the selected source doctor
            if (selectedValue) {
                Array.from(targetSelect.options).forEach(option => {
                    if (option.value === selectedValue) {
                        option.disabled = true;
                        option.selected = false;
                    }
                });
            }
        });
    }
});
</script>