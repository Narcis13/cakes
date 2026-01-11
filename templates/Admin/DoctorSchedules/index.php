<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\DoctorSchedule> $doctorSchedules
 * @var array $staff
 * @var array $services
 * @var array $daysOfWeek
 */
?>
<div class="doctorSchedules index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Program Medici') ?></h3>
        <div>
            <?= $this->Html->link(__('Adaugă Program'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Adăugare în Masă'), ['action' => 'bulkAdd'], ['class' => 'btn btn-success']) ?>
            <?= $this->Html->link(__('Copiază Program'), ['action' => 'copySchedule'], ['class' => 'btn btn-info']) ?>
            <?= $this->Html->link(__('Vizualizare Calendar'), ['action' => 'calendar'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <!-- Filtre -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3']) ?>
            <div class="col-md-3">
                <?= $this->Form->control('staff_id', [
                    'label' => __('Medic'),
                    'options' => ['' => __('Toți medicii')] + $staff,
                    'value' => $this->request->getQuery('staff_id'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $this->Form->control('day_of_week', [
                    'label' => __('Zi din săptămână'),
                    'options' => ['' => __('Toate zilele')] + $daysOfWeek,
                    'value' => $this->request->getQuery('day_of_week'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $this->Form->control('service_id', [
                    'label' => __('Serviciu'),
                    'options' => ['' => __('Toate serviciile')] + $services,
                    'value' => $this->request->getQuery('service_id'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-2">
                <?= $this->Form->control('is_active', [
                    'label' => __('Stare'),
                    'options' => [
                        '' => __('Toate'),
                        '1' => __('Activ'),
                        '0' => __('Inactiv')
                    ],
                    'value' => $this->request->getQuery('is_active'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <?= $this->Form->button(__('Filtrează'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('staff_id', __('Medic')) ?></th>
                    <th><?= $this->Paginator->sort('day_of_week', __('Zi')) ?></th>
                    <th><?= $this->Paginator->sort('start_time', __('Ora început')) ?></th>
                    <th><?= $this->Paginator->sort('end_time', __('Ora sfârșit')) ?></th>
                    <th><?= $this->Paginator->sort('service_id', __('Serviciu')) ?></th>
                    <th><?= __('Intervale') ?></th>
                    <th><?= $this->Paginator->sort('is_active', __('Stare')) ?></th>
                    <th class="actions"><?= __('Acțiuni') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctorSchedules as $doctorSchedule): ?>
                <tr>
                    <td><?= $doctorSchedule->has('staff') ? h($doctorSchedule->staff->name) : '' ?></td>
                    <td><?= h($doctorSchedule->day_name) ?></td>
                    <td><?= h($doctorSchedule->start_time->format('H:i')) ?></td>
                    <td><?= h($doctorSchedule->end_time->format('H:i')) ?></td>
                    <td><?= $doctorSchedule->has('service') ? h($doctorSchedule->service->name) : '' ?></td>
                    <td>
                        <span class="badge bg-info">
                            <?= __('Max: {0}', $doctorSchedule->max_appointments) ?>
                        </span>
                        <?php if ($doctorSchedule->slot_duration): ?>
                            <span class="badge bg-secondary">
                                <?= __n('{0} min', '{0} min', $doctorSchedule->slot_duration, $doctorSchedule->slot_duration) ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $doctorSchedule->is_active ? 'success' : 'danger' ?>">
                            <?= $doctorSchedule->is_active ? __('Activ') : __('Inactiv') ?>
                        </span>
                    </td>
                    <td class="actions">
                        <?= $this->Html->link(__('Vizualizare'), ['action' => 'view', $doctorSchedule->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        <?= $this->Html->link(__('Editare'), ['action' => 'edit', $doctorSchedule->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                        <?= $this->Form->postLink(
                            $doctorSchedule->is_active ? __('Dezactivează') : __('Activează'),
                            ['action' => 'toggle', $doctorSchedule->id],
                            [
                                'confirm' => __('Sigur doriți să {0} acest program?', $doctorSchedule->is_active ? 'dezactivați' : 'activați'),
                                'class' => 'btn btn-sm btn-outline-warning'
                            ]
                        ) ?>
                        <?= $this->Form->postLink(
                            __('Ștergere'),
                            ['action' => 'delete', $doctorSchedule->id],
                            [
                                'confirm' => __('Sigur doriți să ștergeți acest program?'),
                                'class' => 'btn btn-sm btn-outline-danger'
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('Prima')) ?>
            <?= $this->Paginator->prev('< ' . __('Anterioară')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Următoarea') . ' >') ?>
            <?= $this->Paginator->last(__('Ultima') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrări din totalul de {{count}}')) ?></p>
    </div>
</div>
