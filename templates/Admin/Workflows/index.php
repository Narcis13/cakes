<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet<\App\Model\Entity\Workflow> $workflows
 */
?>
<?php $this->assign('title', 'Workflows'); ?>

<div class="workflows index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-project-diagram"></i> <?= __('Workflows') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> ' . __('New Workflow'),
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-paint-brush"></i> ' . __('Workflow Builder'),
                ['action' => 'builder'],
                ['class' => 'btn btn-info', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (count($workflows) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name') ?></th>
                                <th><?= $this->Paginator->sort('category') ?></th>
                                <th><?= $this->Paginator->sort('status') ?></th>
                                <th><?= $this->Paginator->sort('version') ?></th>
                                <th><?= $this->Paginator->sort('created_by', 'Creator') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th class="actions text-center"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workflows as $workflow): ?>
                            <tr>
                                <td>
                                    <?php if ($workflow->icon): ?>
                                        <i class="<?= h($workflow->icon) ?> me-2"></i>
                                    <?php endif; ?>
                                    <?= $this->Html->link(
                                        h($workflow->name),
                                        ['action' => 'view', $workflow->id]
                                    ) ?>
                                    <?php if ($workflow->is_template): ?>
                                        <span class="badge bg-info ms-2">Template</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($workflow->category): ?>
                                        <span class="badge bg-secondary">
                                            <?= h(ucfirst($workflow->category)) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClasses = [
                                        'draft' => 'bg-secondary',
                                        'active' => 'bg-success',
                                        'inactive' => 'bg-warning',
                                        'archived' => 'bg-dark',
                                    ];
                                    $statusClass = $statusClasses[$workflow->status] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= h(ucfirst($workflow->status)) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        v<?= $this->Number->format($workflow->version) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($workflow->creator): ?>
                                        <?= h($workflow->creator->email) ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($workflow->created->format('Y-m-d H:i')) ?></td>
                                <td class="actions text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?= $this->Html->link(
                                            '<i class="fas fa-eye"></i>',
                                            ['action' => 'view', $workflow->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-primary',
                                                'title' => __('View'),
                                            ]
                                        ) ?>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-edit"></i>',
                                            ['action' => 'edit', $workflow->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-secondary',
                                                'title' => __('Edit'),
                                            ]
                                        ) ?>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-paint-brush"></i>',
                                            ['action' => 'builder', $workflow->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-info',
                                                'title' => __('Visual Builder'),
                                            ]
                                        ) ?>
                                        <?php if ($workflow->is_active): ?>
                                            <?= $this->Form->postLink(
                                                '<i class="fas fa-play"></i>',
                                                ['action' => 'execute', $workflow->id],
                                                [
                                                    'escape' => false,
                                                    'class' => 'btn btn-outline-success',
                                                    'title' => __('Execute'),
                                                    'confirm' => __('Execute workflow "{0}"?', $workflow->name),
                                                ]
                                            ) ?>
                                        <?php endif; ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-copy"></i>',
                                            ['action' => 'clone', $workflow->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-info',
                                                'title' => __('Clone'),
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-trash"></i>',
                                            ['action' => 'delete', $workflow->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-danger',
                                                'title' => __('Delete'),
                                                'confirm' => __('Are you sure you want to delete "{0}"?', $workflow->name),
                                            ]
                                        ) ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <?= $this->Paginator->first('<i class="fas fa-angle-double-left"></i>', ['escape' => false]) ?>
                            <?= $this->Paginator->prev('<i class="fas fa-angle-left"></i>', ['escape' => false]) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next('<i class="fas fa-angle-right"></i>', ['escape' => false]) ?>
                            <?= $this->Paginator->last('<i class="fas fa-angle-double-right"></i>', ['escape' => false]) ?>
                        </ul>
                    </nav>
                    <p class="mb-0 text-muted">
                        <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-project-diagram fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No workflows found</h4>
                    <p class="text-muted">Create your first workflow to get started with automation.</p>
                    <?= $this->Html->link(
                        '<i class="fas fa-plus"></i> Create Workflow',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>