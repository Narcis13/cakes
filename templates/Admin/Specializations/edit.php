<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Specialization $specialization
 */
?>
<?php $this->assign('title', 'Editează specializare'); ?>

<div class="specializations form content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Editează specializare medicală: {0}', h($specialization->name)) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> Vizualizează',
                ['action' => 'view', $specialization->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($specialization) ?>
            <fieldset>
                <legend><?= __('Informații specializare') ?></legend>

                <?= $this->Form->control('name', [
                    'class' => 'form-control',
                    'label' => ['class' => 'form-label', 'text' => 'Nume'],
                    'required' => true,
                    'placeholder' => 'ex., Cardiologie, Neurologie, Pediatrie'
                ]) ?>

                <?= $this->Form->control('description', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => ['class' => 'form-label', 'text' => 'Descriere'],
                    'rows' => 4,
                    'placeholder' => 'Scurtă descriere a acestei specializări medicale...'
                ]) ?>

                <div class="form-check mt-3">
                    <?= $this->Form->control('is_active', [
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'label' => [
                            'class' => 'form-check-label',
                            'text' => 'Activ (disponibil pentru atribuirea personalului)'
                        ],
                        'templates' => [
                            'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
                            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                        ]
                    ]) ?>
                </div>
            </fieldset>

            <div class="text-muted mt-3">
                <small><strong>Creat:</strong> <?= h($specialization->created->format('j M Y H:i')) ?></small><br>
                <small><strong>Modificat:</strong> <?= h($specialization->modified->format('j M Y H:i')) ?></small>
            </div>

            <div class="mt-4">
                <?= $this->Form->button(__('Actualizează specializare'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Anulează'),
                    ['action' => 'view', $specialization->id],
                    ['class' => 'btn btn-outline-secondary ms-2']
                ) ?>
                <?= $this->Form->postLink(
                    __('Șterge'),
                    ['action' => 'delete', $specialization->id],
                    [
                        'confirm' => __('Sunteți sigur că doriți să ștergeți "{0}"? Această acțiune nu poate fi anulată.', $specialization->name),
                        'class' => 'btn btn-outline-danger ms-2'
                    ]
                ) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
