<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Page> $pages
 */
?>
<?php $this->assign('title', 'Pages'); ?>

<div class="pages index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Pages') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> New Page',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (!$pages->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('title') ?></th>
                                <th><?= $this->Paginator->sort('slug') ?></th>
                                <th><?= $this->Paginator->sort('is_published') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pages as $page): ?>
                            <tr>
                                <td>
                                    <?= $this->Html->link(
                                        h($page->title),
                                        ['action' => 'view', $page->id]
                                    ) ?>
                                </td>
                                <td>
                                    <code><?= h($page->slug) ?></code>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-external-link-alt"></i>',
                                        '/' . $page->slug,
                                        ['target' => '_blank', 'escape' => false, 'class' => 'ms-2 text-muted']
                                    ) ?>
                                </td>
                                <td>
                                    <?php if ($page->is_published): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($page->created->format('M j, Y g:i A')) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $page->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $page->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Edit']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $page->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete "{0}"?', $page->title),
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
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No pages found</h5>
                    <p class="text-muted">Create your first page to get started.</p>
                    <?= $this->Html->link(
                        'Create Page',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
