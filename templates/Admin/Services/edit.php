<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 * @var array $departments
 */
?>
<?php $this->assign('title', 'Editează serviciu'); ?>

<div class="service edit content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Editează serviciu medical') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> Vizualizează',
                ['action' => 'view', $service->id],
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
            <?= $this->Form->create($service) ?>

            <div class="row">
                <div class="col-md-8">
                    <fieldset>
                        <legend><?= __('Informații serviciu') ?></legend>

                        <?= $this->Form->control('name', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Nume serviciu'],
                            'required' => true,
                            'placeholder' => 'ex., Consultație generală, Radiografie, Analize de sânge'
                        ]) ?>

                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Descriere'],
                            'rows' => 4,
                            'placeholder' => 'Descrieți serviciul medical...'
                        ]) ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('department_id', [
                                    'type' => 'select',
                                    'options' => $departments,
                                    'empty' => 'Selectați departamentul',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Departament']
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('duration_minutes', [
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Durată (minute)'],
                                    'placeholder' => 'ex., 30, 60, 90',
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
                                    'label' => ['class' => 'form-label', 'text' => 'Preț (RON)'],
                                    'placeholder' => 'ex., 100.00',
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
                                        'text' => 'Serviciul este activ'
                                    ],
                                    'templates' => [
                                        'checkboxWrapper' => '<div class="form-check mb-3">{{label}}</div>',
                                        'nestingLabel' => '{{hidden}}<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}><label{{attrs}}>{{text}}</label>',
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset>
                        <legend><?= __('Cerințe și instrucțiuni') ?></legend>

                        <?= $this->Form->control('requirements', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Cerințe pentru pacienți'],
                            'rows' => 6,
                            'placeholder' => 'ex., Este necesar post alimentar, Aduceți rapoartele anterioare, etc.'
                        ]) ?>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>Sfaturi:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Introduceți instrucțiuni de pregătire</li>
                                <li>Listați cerințele preliminare</li>
                                <li>Menționați ce trebuie să aducă pacienții</li>
                            </ul>
                        </div>

                        <hr>

                        <div class="text-muted">
                            <p class="mb-1"><strong>Detalii serviciu:</strong></p>
                            <small>Creat: <?= h($service->created->format('j M Y H:i')) ?></small><br>
                            <small>Modificat: <?= h($service->modified->format('j M Y H:i')) ?></small>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="mt-4">
                <?= $this->Form->button(__('Actualizează serviciu'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Anulează'),
                    ['action' => 'view', $service->id],
                    ['class' => 'btn btn-outline-secondary ms-2']
                ) ?>
            </div>

            <?= $this->Form->end() ?>

            <div class="mt-3">
                <?= $this->Form->postLink(__('Șterge'),
                    ['action' => 'delete', $service->id],
                    [
                        'confirm' => __('Sunteți sigur că doriți să ștergeți "{0}"? Această acțiune nu poate fi anulată.', $service->name),
                        'class' => 'btn btn-outline-danger'
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>

<script>
// Calculează automat afișarea duratei în ore/minute
document.getElementById('duration-minutes').addEventListener('input', function(e) {
    const minutes = parseInt(e.target.value) || 0;
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;

    let display = '';
    if (hours > 0) {
        display = hours + ' oră' + (hours > 1 ? ' ore' : '');
        if (mins > 0) {
            display += ' ' + mins + ' min' + (mins > 1 ? 'ute' : 'ut');
        }
    } else if (mins > 0) {
        display = mins + ' minut' + (mins > 1 ? 'e' : '');
    }

    // Puteți afișa acest lucru într-un element text ajutător dacă doriți
});
</script>
