<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Service> $services
 * @var array $departments
 */
?>
<?php $this->assign('title', 'Servicii'); ?>

<div class="services index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Servicii medicale') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Serviciu nou',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <!-- Filtre -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
                <div class="col-md-4">
                    <?= $this->Form->control('department_id', [
                        'label' => 'Filtrează după departament',
                        'options' => ['' => 'Toate departamentele'] + $departments,
                        'value' => $this->request->getQuery('department_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $this->Form->control('is_active', [
                        'label' => 'Status',
                        'options' => ['' => 'Toate', '1' => 'Activ', '0' => 'Inactiv'],
                        'value' => $this->request->getQuery('is_active'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-2">
                        <?= $this->Form->button('<i class="fas fa-filter"></i> Filtrează', [
                            'type' => 'submit',
                            'class' => 'btn btn-secondary',
                            'escapeTitle' => false
                        ]) ?>
                        <?= $this->Html->link('<i class="fas fa-times"></i> Resetează',
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (!$services->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name', 'Nume serviciu') ?></th>
                                <th><?= $this->Paginator->sort('department_id', 'Departament') ?></th>
                                <th><?= $this->Paginator->sort('duration_minutes', 'Durată') ?></th>
                                <th><?= $this->Paginator->sort('price', 'Preț') ?></th>
                                <th><?= $this->Paginator->sort('is_active', 'Status') ?></th>
                                <th><?= $this->Paginator->sort('created', 'Creat') ?></th>
                                <th class="actions"><?= __('Acțiuni') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                            <tr>
                                <td>
                                    <div>
                                        <?= $this->Html->link(
                                            h($service->name),
                                            ['action' => 'view', $service->id],
                                            ['class' => 'fw-bold text-decoration-none']
                                        ) ?>
                                        <?php if ($service->description): ?>
                                            <small class="text-muted d-block"><?= $this->Text->truncate(h($service->description), 60) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($service->department): ?>
                                        <span class="badge bg-info text-dark">
                                            <i class="fas fa-building"></i> <?= h($service->department->name) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Nealocat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($service->duration_minutes): ?>
                                        <i class="fas fa-clock"></i> <?= h($service->duration_minutes) ?> min
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($service->price): ?>
                                        <strong><?= $this->Number->format($service->price, ['places' => 2]) ?> RON</strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($service->is_active): ?>
                                        <span class="badge bg-success">Activ</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactiv</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($service->created->format('j M Y')) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $service->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Vizualizează']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $service->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Editează']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-power-off"></i>',
                                        ['action' => 'toggleActive', $service->id],
                                        [
                                            'confirm' => $service->is_active
                                                ? __('Sunteți sigur că doriți să dezactivați "{0}"?', $service->name)
                                                : __('Sunteți sigur că doriți să activați "{0}"?', $service->name),
                                            'class' => 'btn btn-sm btn-outline-warning',
                                            'escape' => false,
                                            'title' => $service->is_active ? 'Dezactivează' : 'Activează'
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $service->id],
                                        [
                                            'confirm' => __('Sunteți sigur că doriți să ștergeți "{0}"?', $service->name),
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Șterge'
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="paginator mt-3">
                    <ul class="pagination">
                        <?= $this->Paginator->first('<< ' . __('prima')) ?>
                        <?= $this->Paginator->prev('< ' . __('anterioara')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('următoarea') . ' >') ?>
                        <?= $this->Paginator->last(__('ultima') . ' >>') ?>
                    </ul>
                    <p class="text-muted"><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrare(i) din {{count}} total')) ?></p>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nu s-au găsit servicii</h5>
                    <p class="text-muted">Creați primul serviciu medical pentru a începe.</p>
                    <?= $this->Html->link(
                        'Creează serviciu',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
