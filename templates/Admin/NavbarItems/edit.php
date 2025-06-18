<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NavbarItem $navbarItem
 * @var string[]|\Cake\Collection\CollectionInterface $parentNavbarItems
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit"></i> Edit Navbar Item</h2>
    <div>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary me-2', 'escape' => false]
        ) ?>
        <?= $this->Form->postLink(
            '<i class="fas fa-trash"></i> Delete',
            ['action' => 'delete', $navbarItem->id],
            [
                'confirm' => __('Are you sure you want to delete "{0}"?', $navbarItem->title),
                'class' => 'btn btn-danger',
                'escape' => false
            ]
        ) ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->Form->create($navbarItem, ['class' => 'needs-validation', 'novalidate' => true]) ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('title', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Title *'],
                        'required' => true
                    ]) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('url', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'URL'],
                        'placeholder' => 'e.g., /about or https://example.com'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('parent_id', [
                        'options' => $parentNavbarItems,
                        'empty' => '-- Select Parent (Optional) --',
                        'class' => 'form-select',
                        'label' => ['class' => 'form-label', 'text' => 'Parent Item']
                    ]) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('target', [
                        'type' => 'select',
                        'options' => [
                            '_self' => 'Same Window',
                            '_blank' => 'New Window/Tab'
                        ],
                        'class' => 'form-select',
                        'label' => ['class' => 'form-label', 'text' => 'Link Target']
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('icon', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Icon Class'],
                        'placeholder' => 'e.g., fas fa-home, bi bi-house'
                    ]) ?>
                    <div class="form-text">Bootstrap Icons or Font Awesome classes</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <?= $this->Form->control('sort_order', [
                        'type' => 'number',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Sort Order'],
                        'min' => 0
                    ]) ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <?= $this->Form->control('is_active', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => ['class' => 'form-check-label', 'text' => 'Active']
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <?= $this->Html->link(
                'Cancel',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary me-md-2']
            ) ?>
            <?= $this->Form->button(
                '<i class="fas fa-save"></i> Update Item',
                ['type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>
