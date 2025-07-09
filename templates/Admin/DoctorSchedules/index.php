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
        <h3><?= __('Doctor Schedules') ?></h3>
        <div>
            <?= $this->Html->link(__('Add Schedule'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Bulk Add'), ['action' => 'bulkAdd'], ['class' => 'btn btn-success']) ?>
            <?= $this->Html->link(__('Copy Schedule'), ['action' => 'copySchedule'], ['class' => 'btn btn-info']) ?>
            <?= $this->Html->link(__('Calendar View'), ['action' => 'calendar'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3']) ?>
            <div class="col-md-3">
                <?= $this->Form->control('staff_id', [
                    'label' => __('Doctor'),
                    'options' => ['' => __('All Doctors')] + $staff,
                    'value' => $this->request->getQuery('staff_id'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $this->Form->control('day_of_week', [
                    'label' => __('Day of Week'),
                    'options' => ['' => __('All Days')] + $daysOfWeek,
                    'value' => $this->request->getQuery('day_of_week'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $this->Form->control('service_id', [
                    'label' => __('Service'),
                    'options' => ['' => __('All Services')] + $services,
                    'value' => $this->request->getQuery('service_id'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-2">
                <?= $this->Form->control('is_active', [
                    'label' => __('Status'),
                    'options' => [
                        '' => __('All'),
                        '1' => __('Active'),
                        '0' => __('Inactive')
                    ],
                    'value' => $this->request->getQuery('is_active'),
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('staff_id', __('Doctor')) ?></th>
                    <th><?= $this->Paginator->sort('day_of_week', __('Day')) ?></th>
                    <th><?= $this->Paginator->sort('start_time', __('Start Time')) ?></th>
                    <th><?= $this->Paginator->sort('end_time', __('End Time')) ?></th>
                    <th><?= $this->Paginator->sort('service_id', __('Service')) ?></th>
                    <th><?= __('Slots') ?></th>
                    <th><?= $this->Paginator->sort('is_active', __('Status')) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
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
                                <?= __n('{0} min', '{0} mins', $doctorSchedule->slot_duration, $doctorSchedule->slot_duration) ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $doctorSchedule->is_active ? 'success' : 'danger' ?>">
                            <?= $doctorSchedule->is_active ? __('Active') : __('Inactive') ?>
                        </span>
                    </td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $doctorSchedule->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $doctorSchedule->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                        <?= $this->Form->postLink(
                            $doctorSchedule->is_active ? __('Deactivate') : __('Activate'),
                            ['action' => 'toggle', $doctorSchedule->id],
                            [
                                'confirm' => __('Are you sure you want to {0} this schedule?', $doctorSchedule->is_active ? 'deactivate' : 'activate'),
                                'class' => 'btn btn-sm btn-outline-warning'
                            ]
                        ) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $doctorSchedule->id],
                            [
                                'confirm' => __('Are you sure you want to delete this schedule?'),
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
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>