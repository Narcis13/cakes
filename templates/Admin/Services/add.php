<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 * @var array $departments
 */
?>
<?php $this->assign('title', 'Add Service'); ?>

<div class="service add content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Add Medical Service') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($service) ?>
            
            <div class="row">
                <div class="col-md-8">
                    <fieldset>
                        <legend><?= __('Service Information') ?></legend>
                        
                        <?= $this->Form->control('name', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Service Name'],
                            'required' => true,
                            'placeholder' => 'e.g., General Consultation, X-Ray, Blood Test'
                        ]) ?>
                        
                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'rows' => 4,
                            'placeholder' => 'Describe the medical service...'
                        ]) ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('department_id', [
                                    'type' => 'select',
                                    'options' => $departments,
                                    'empty' => 'Select Department',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Department']
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('duration_minutes', [
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Duration (minutes)'],
                                    'placeholder' => 'e.g., 30, 60, 90',
                                    'min' => 0,
                                    'step' => 5
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('price', [
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Price ($)'],
                                    'placeholder' => 'e.g., 100.00',
                                    'min' => 0,
                                    'step' => 0.01
                                ]) ?>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <?= $this->Form->control('is_active', [
                                    'type' => 'checkbox',
                                    'class' => 'form-check-input',
                                    'label' => [
                                        'class' => 'form-check-label',
                                        'text' => 'Service is active'
                                    ],
                                    'templates' => [
                                        'checkboxWrapper' => '<div class="form-check mb-3">{{label}}</div>',
                                        'nestingLabel' => '{{hidden}}<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}><label{{attrs}}>{{text}}</label>',
                                    ],
                                    'checked' => true
                                ]) ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-md-4">
                    <fieldset>
                        <legend><?= __('Requirements & Instructions') ?></legend>
                        
                        <?= $this->Form->control('requirements', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Patient Requirements'],
                            'rows' => 6,
                            'placeholder' => 'e.g., Fasting required, Bring previous reports, etc.'
                        ]) ?>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>Tips:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Enter preparation instructions</li>
                                <li>List any prerequisites</li>
                                <li>Mention what patients should bring</li>
                            </ul>
                        </div>
                    </fieldset>
                </div>
            </div>
            
            <div class="mt-4">
                <?= $this->Form->button(__('Save Service'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Cancel'), 
                    ['action' => 'index'], 
                    ['class' => 'btn btn-outline-secondary ms-2']
                ) ?>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
// Auto-calculate duration in hours/minutes display
document.getElementById('duration-minutes').addEventListener('input', function(e) {
    const minutes = parseInt(e.target.value) || 0;
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    
    let display = '';
    if (hours > 0) {
        display = hours + ' hour' + (hours > 1 ? 's' : '');
        if (mins > 0) {
            display += ' ' + mins + ' min' + (mins > 1 ? 's' : '');
        }
    } else if (mins > 0) {
        display = mins + ' minute' + (mins > 1 ? 's' : '');
    }
    
    // You could show this in a helper text element if desired
});
</script>