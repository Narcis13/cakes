<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Specialization $specialization
 */
?>
<?php $this->assign('title', 'Edit Specialization'); ?>

<div class="specializations form content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Edit Medical Specialization: {0}', h($specialization->name)) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> View',
                ['action' => 'view', $specialization->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
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
                        ]
                    ]) ?>
                </div>
            </fieldset>

            <div class="text-muted mt-3">
                <small><strong>Created:</strong> <?= h($specialization->created->format('M j, Y g:i A')) ?></small><br>
                <small><strong>Modified:</strong> <?= h($specialization->modified->format('M j, Y g:i A')) ?></small>
            </div>
            
            <div class="mt-4">
                <?= $this->Form->button(__('Update Specialization'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Cancel'), 
                    ['action' => 'view', $specialization->id], 
                    ['class' => 'btn btn-outline-secondary ms-2']
                ) ?>
                <?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $specialization->id],
                    [
                        'confirm' => __('Are you sure you want to delete "{0}"? This action cannot be undone.', $specialization->name),
                        'class' => 'btn btn-outline-danger ms-2'
                    ]
                ) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>