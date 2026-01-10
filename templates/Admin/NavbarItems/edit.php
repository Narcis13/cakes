<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NavbarItem $navbarItem
 * @var string[]|\Cake\Collection\CollectionInterface $parentNavbarItems
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit"></i> Editează element meniu</h2>
    <div>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Înapoi la listă',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary me-2', 'escape' => false]
        ) ?>
        <?= $this->Form->postLink(
            '<i class="fas fa-trash"></i> Șterge',
            ['action' => 'delete', $navbarItem->id],
            [
                'confirm' => __('Sigur doriți să ștergeți "{0}"?', $navbarItem->title),
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
                        'label' => ['class' => 'form-label', 'text' => 'Titlu *'],
                        'required' => true
                    ]) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('url', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'URL'],
                        'placeholder' => 'ex: /despre sau https://exemplu.com'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('parent_id', [
                        'options' => $parentNavbarItems,
                        'empty' => '-- Selectează părinte (Opțional) --',
                        'class' => 'form-select',
                        'label' => ['class' => 'form-label', 'text' => 'Element părinte']
                    ]) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('target', [
                        'type' => 'select',
                        'options' => [
                            '_self' => 'Aceeași fereastră',
                            '_blank' => 'Fereastră nouă/Tab'
                        ],
                        'class' => 'form-select',
                        'label' => ['class' => 'form-label', 'text' => 'Destinație link']
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('icon', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Clasă pictogramă'],
                        'placeholder' => 'ex: fas fa-home, bi bi-house'
                    ]) ?>
                    <div class="form-text">Clase Bootstrap Icons sau Font Awesome</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <?= $this->Form->control('sort_order', [
                        'type' => 'number',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Ordine sortare'],
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
                            'label' => ['class' => 'form-check-label', 'text' => 'Activ']
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <?= $this->Html->link(
                'Anulează',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary me-md-2']
            ) ?>
            <?= $this->Form->button(
                '<i class="fas fa-save"></i> Actualizează element',
                ['type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>
