<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Department> $departments
 */
?>
<?php $this->assign('title', 'Departments'); ?>

<div class="departments index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Departments') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> New Department',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (!$departments->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name', 'Department Name') ?></th>
                                <th><?= $this->Paginator->sort('head_doctor_id', 'Head Doctor') ?></th>
                                <th><?= $this->Paginator->sort('phone') ?></th>
                                <th><?= $this->Paginator->sort('floor_location', 'Location') ?></th>
                                <th><?= $this->Paginator->sort('is_active', 'Status') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departments as $department): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($department->picture): ?>
                                            <img src="<?= $this->Url->build('/img/departments/' . $department->picture) ?>" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;" 
                                                 alt="<?= h($department->name) ?>">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center text-white" 
                                                 style="width: 40px; height: 40px; font-size: 12px;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <?= $this->Html->link(
                                                h($department->name),
                                                ['action' => 'view', $department->id],
                                                ['class' => 'fw-bold text-decoration-none']
                                            ) ?>
                                            <?php if ($department->description): ?>
                                                <small class="text-muted d-block"><?= $this->Text->truncate(h($department->description), 60) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($department->head_doctor): ?>
                                        <?= h($department->head_doctor->first_name . ' ' . $department->head_doctor->last_name) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($department->phone): ?>
                                        <a href="tel:<?= h($department->phone) ?>" class="text-decoration-none">
                                            <i class="fas fa-phone"></i> <?= h($department->phone) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($department->floor_location): ?>
                                        <i class="fas fa-map-marker-alt"></i> <?= h($department->floor_location) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($department->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($department->created->format('M j, Y')) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $department->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $department->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Edit']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $department->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete "{0}"?', $department->name),
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
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No departments found</h5>
                    <p class="text-muted">Create your first department to get started.</p>
                    <?= $this->Html->link(
                        'Create Department',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
