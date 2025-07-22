<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Specialization> $specializations
 */
?>
<?php $this->assign('title', 'Medical Specializations'); ?>

<div class="specializations index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Medical Specializations') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Add New Specialization',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name', 'Specialization Name') ?></th>
                            <th><?= $this->Paginator->sort('is_active', 'Status') ?></th>
                            <th>Staff Count</th>
                            <th><?= $this->Paginator->sort('created') ?></th>
                            <th class="actions text-center"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($specializations as $specialization): ?>
                        <tr>
                            <td>
                                <strong><?= h($specialization->name) ?></strong>
                                <?php if ($specialization->description): ?>
                                    <br><small class="text-muted"><?= h($specialization->description) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($specialization->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $staffCount = isset($specialization->staff) ? count($specialization->staff) : 0;
                                ?>
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-user-md"></i> <?= $staffCount ?> doctors
                                </span>
                            </td>
                            <td><?= h($specialization->created->format('M j, Y')) ?></td>
                            <td class="actions text-center">
                                <?= $this->Html->link(
                                    '<i class="fas fa-eye"></i>',
                                    ['action' => 'view', $specialization->id],
                                    ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'View']
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    ['action' => 'edit', $specialization->id],
                                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Edit']
                                ) ?>
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    ['action' => 'delete', $specialization->id],
                                    [
                                        'confirm' => __('Are you sure you want to delete "{0}"?', $specialization->name),
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

            <?php if (count($specializations) === 0): ?>
                <div class="text-center py-5">
                    <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No specializations found</p>
                    <?= $this->Html->link(
                        '<i class="fas fa-plus"></i> Add First Specialization',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                </div>
            <?php endif; ?>

            <?php if ($this->Paginator->hasPage(2)): ?>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <ul class="pagination mb-0">
                            <?= $this->Paginator->first('<i class="fas fa-angle-double-left"></i>', ['escape' => false, 'class' => 'page-link']) ?>
                            <?= $this->Paginator->prev('<i class="fas fa-angle-left"></i>', ['escape' => false, 'class' => 'page-link']) ?>
                            <?= $this->Paginator->numbers(['class' => 'page-link']) ?>
                            <?= $this->Paginator->next('<i class="fas fa-angle-right"></i>', ['escape' => false, 'class' => 'page-link']) ?>
                            <?= $this->Paginator->last('<i class="fas fa-angle-double-right"></i>', ['escape' => false, 'class' => 'page-link']) ?>
                        </ul>
                    </div>
                    <div class="text-muted">
                        <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} of {{count}} total')) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>