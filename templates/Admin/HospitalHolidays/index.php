<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\HospitalHoliday> $hospitalHolidays
 * @var string $year
 * @var array $yearsList
 */
$this->assign('title', 'Hospital Holidays');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-alt"></i>
                Hospital Holidays
            </h1>
            <div class="d-flex gap-2">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex gap-2']) ?>
                <?= $this->Form->control('year', [
                    'type' => 'select',
                    'options' => $yearsList,
                    'value' => $year,
                    'label' => false,
                    'class' => 'form-control',
                    'onchange' => 'this.form.submit()'
                ]) ?>
                <?= $this->Form->end() ?>
                <?= $this->Html->link(
                    '<i class="fas fa-plus"></i> Add Holiday',
                    ['action' => 'add'],
                    ['class' => 'btn btn-primary', 'escape' => false]
                ) ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name') ?></th>
                                <th><?= $this->Paginator->sort('date') ?></th>
                                <th>Day of Week</th>
                                <th><?= $this->Paginator->sort('is_recurring', 'Recurring') ?></th>
                                <th>Description</th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th class="actions text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hospitalHolidays as $hospitalHoliday): ?>
                            <tr>
                                <td>
                                    <strong><?= h($hospitalHoliday->name) ?></strong>
                                </td>
                                <td>
                                    <?= h($hospitalHoliday->date->format('F j, Y')) ?>
                                </td>
                                <td>
                                    <?= h($hospitalHoliday->date->format('l')) ?>
                                </td>
                                <td>
                                    <?php if ($hospitalHoliday->is_recurring): ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-redo"></i> Recurring Annually
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">One-time</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= h($hospitalHoliday->description) ?: '<em class="text-muted">No description</em>' ?>
                                </td>
                                <td><?= h($hospitalHoliday->created->format('M j, Y')) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $hospitalHoliday->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-info', 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $hospitalHoliday->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-primary', 'title' => 'Edit']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $hospitalHoliday->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete the holiday "{0}"?', $hospitalHoliday->name),
                                            'escape' => false,
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Delete'
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (count($hospitalHolidays) === 0): ?>
                <div class="text-center py-4">
                    <p class="text-muted">No holidays found for <?= h($year) ?>.</p>
                </div>
                <?php endif; ?>
                
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
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5><i class="fas fa-info-circle"></i> Holiday Types</h5>
                <ul>
                    <li><strong>One-time Holiday:</strong> Applies only to the specific date in the given year</li>
                    <li><strong>Recurring Holiday:</strong> Automatically applies to the same date (month and day) every year</li>
                </ul>
            </div>
        </div>
    </div>
</div>