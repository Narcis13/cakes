<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Specialization $specialization
 */
?>
<?php $this->assign('title', 'Add Specialization'); ?>

<div class="specializations form content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Add Medical Specialization') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($specialization) ?>
            <fieldset>
                <legend><?= __('Specialization Information') ?></legend>
                
                <?= $this->Form->control('name', [
                    'class' => 'form-control',
                    'label' => ['class' => 'form-label'],
                    'required' => true,
                    'placeholder' => 'e.g., Cardiologie, Neurologie, Pediatrie'
                ]) ?>
                
                <?= $this->Form->control('description', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => ['class' => 'form-label'],
                    'rows' => 4,
                    'placeholder' => 'Brief description of this medical specialization...'
                ]) ?>
                
                <div class="form-check mt-3">
                    <?= $this->Form->control('is_active', [
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'label' => [
                            'class' => 'form-check-label',
                            'text' => 'Active (available for staff assignment)'
                        ],
                        'templates' => [
                            'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
                            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                        ],
                        'checked' => true
                    ]) ?>
                </div>
            </fieldset>
            
            <div class="mt-4">
                <?= $this->Form->button(__('Save Specialization'), [
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