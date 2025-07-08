<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Staff> $staff
 * @var array $departments
 * @var array $staffTypes
 */
?>
<?php $this->assign('title', 'Staff Management'); ?>

<div class="staff index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Staff Management') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Add Staff Member',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
                <div class="col-md-3">
                    <?= $this->Form->control('department_id', [
                        'label' => 'Filter by Department',
                        'options' => ['' => 'All Departments'] + $departments,
                        'value' => $this->request->getQuery('department_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $this->Form->control('staff_type', [
                        'label' => 'Staff Type',
                        'options' => ['' => 'All Types'] + $staffTypes,
                        'value' => $this->request->getQuery('staff_type'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('is_active', [
                        'label' => 'Status',
                        'options' => ['' => 'All', '1' => 'Active', '0' => 'Inactive'],
                        'value' => $this->request->getQuery('is_active'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <?= $this->Form->button('<i class="fas fa-filter"></i> Filter', [
                            'type' => 'submit',
                            'class' => 'btn btn-secondary',
                            'escape' => false
                        ]) ?>
                        <?= $this->Html->link('<i class="fas fa-times"></i> Clear', 
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
            <?php if (!$staff->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name', 'Staff Member') ?></th>
                                <th><?= $this->Paginator->sort('title', 'Title/Position') ?></th>
                                <th><?= $this->Paginator->sort('department_id', 'Department') ?></th>
                                <th><?= $this->Paginator->sort('staff_type', 'Type') ?></th>
                                <th>Contact</th>
                                <th><?= $this->Paginator->sort('years_experience', 'Experience') ?></th>
                                <th><?= $this->Paginator->sort('is_active', 'Status') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staff as $staffMember): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($staffMember->photo): ?>
                                            <img src="<?= $this->Url->build('/img/staff/' . $staffMember->photo) ?>" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;" 
                                                 alt="<?= h($staffMember->name) ?>">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center text-white" 
                                                 style="width: 40px; height: 40px; font-size: 16px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <?= $this->Html->link(
                                                h($staffMember->name),
                                                ['action' => 'view', $staffMember->id],
                                                ['class' => 'fw-bold text-decoration-none']
                                            ) ?>
                                            <?php if ($staffMember->specialization): ?>
                                                <small class="text-muted d-block"><?= h($staffMember->specialization) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= h($staffMember->title ?: '-') ?>
                                </td>
                                <td>
                                    <?php if ($staffMember->department): ?>
                                        <span class="badge bg-info text-dark">
                                            <i class="fas fa-building"></i> <?= h($staffMember->department->name) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= h(ucfirst($staffMember->staff_type)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <?php if ($staffMember->phone): ?>
                                            <a href="tel:<?= h($staffMember->phone) ?>" class="text-decoration-none">
                                                <i class="fas fa-phone"></i> <?= h($staffMember->phone) ?>
                                            </a><br>
                                        <?php endif; ?>
                                        <?php if ($staffMember->email): ?>
                                            <a href="mailto:<?= h($staffMember->email) ?>" class="text-decoration-none">
                                                <i class="fas fa-envelope"></i> <?= h($staffMember->email) ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!$staffMember->phone && !$staffMember->email): ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($staffMember->years_experience): ?>
                                        <?= h($staffMember->years_experience) ?> years
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($staffMember->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $staffMember->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $staffMember->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Edit']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-power-off"></i>',
                                        ['action' => 'toggleActive', $staffMember->id],
                                        [
                                            'confirm' => $staffMember->is_active 
                                                ? __('Are you sure you want to deactivate "{0}"?', $staffMember->name)
                                                : __('Are you sure you want to activate "{0}"?', $staffMember->name),
                                            'class' => 'btn btn-sm btn-outline-warning',
                                            'escape' => false,
                                            'title' => $staffMember->is_active ? 'Deactivate' : 'Activate'
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $staffMember->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete "{0}"?', $staffMember->name),
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Delete'
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
                        <?= $this->Paginator->first('<< ' . __('first')) ?>
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                        <?= $this->Paginator->last(__('last') . ' >>') ?>
                    </ul>
                    <p class="text-muted"><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No staff members found</h5>
                    <p class="text-muted">Add your first staff member to get started.</p>
                    <?= $this->Html->link(
                        'Add Staff Member',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>