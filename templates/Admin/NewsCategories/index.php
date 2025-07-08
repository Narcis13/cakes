<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\NewsCategory> $newsCategories
 */
?>
<?php $this->assign('title', 'News Categories'); ?>

<div class="newsCategories index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('News Categories') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-newspaper"></i> News Articles',
                ['controller' => 'News', 'action' => 'index'],
                ['class' => 'btn btn-secondary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> New Category',
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (!$newsCategories->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name', 'Category Name') ?></th>
                                <th><?= $this->Paginator->sort('slug') ?></th>
                                <th>Articles</th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($newsCategories as $newsCategory): ?>
                            <tr>
                                <td>
                                    <?= $this->Html->link(
                                        h($newsCategory->name),
                                        ['action' => 'view', $newsCategory->id],
                                        ['class' => 'fw-bold text-decoration-none']
                                    ) ?>
                                    <?php if ($newsCategory->description): ?>
                                        <br><small class="text-muted"><?= $this->Text->truncate(h($newsCategory->description), 60) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><code><?= h($newsCategory->slug) ?></code></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= isset($newsCategory->news_count) ? $newsCategory->news_count : 0 ?> articles
                                    </span>
                                </td>
                                <td><?= h($newsCategory->created->format('M j, Y')) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $newsCategory->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $newsCategory->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Edit']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $newsCategory->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete "{0}"?', $newsCategory->name),
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
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No news categories found</h5>
                    <p class="text-muted">Create your first news category to organize articles.</p>
                    <?= $this->Html->link(
                        'Create Category',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>