<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\NavbarItem> $navbarItems
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-bars"></i> Navbar Items</h2>
    <?= $this->Html->link(
        '<i class="fas fa-plus"></i> Add New Item',
        ['action' => 'add'],
        ['class' => 'btn btn-primary', 'escape' => false]
    ) ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><?= $this->Paginator->sort('title', 'Title') ?></th>
                        <th><?= $this->Paginator->sort('url', 'URL') ?></th>
                        <th><?= $this->Paginator->sort('parent_id', 'Parent') ?></th>
                        <th><?= $this->Paginator->sort('sort_order', 'Order') ?></th>
                        <th><?= $this->Paginator->sort('is_active', 'Active') ?></th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($navbarItems as $navbarItem): ?>
                    <tr>
                        <td>
                            <strong><?= h($navbarItem->title) ?></strong>
                            <?php if ($navbarItem->icon): ?>
                                <i class="<?= h($navbarItem->icon) ?>" title="<?= h($navbarItem->icon) ?>"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($navbarItem->url): ?>
                                <a href="<?= h($navbarItem->url) ?>" target="<?= h($navbarItem->target ?: '_self') ?>" class="text-decoration-none">
                                    <?= h($navbarItem->url) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No URL</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $navbarItem->hasValue('parent_navbar_item') ? h($navbarItem->parent_navbar_item->title) : '<span class="text-muted">Top Level</span>' ?>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= $this->Number->format($navbarItem->sort_order) ?></span>
                        </td>
                        <td>
                            <?php if ($navbarItem->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <?= $this->Html->link(
                                    '<i class="fas fa-eye"></i>',
                                    ['action' => 'view', $navbarItem->id],
                                    ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'View']
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    ['action' => 'edit', $navbarItem->id],
                                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Edit']
                                ) ?>
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    ['action' => 'delete', $navbarItem->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete "{0}"?', $navbarItem->title),
                                        'class' => 'btn btn-sm btn-outline-danger',
                                        'escape' => false,
                                        'title' => 'Delete'
                                    ]
                                ) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($this->Paginator->total() > $this->Paginator->param('perPage')): ?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<< First', ['class' => 'page-link']) ?>
        <?= $this->Paginator->prev('< Previous', ['class' => 'page-link']) ?>
        <?= $this->Paginator->numbers(['class' => 'page-link']) ?>
        <?= $this->Paginator->next('Next >', ['class' => 'page-link']) ?>
        <?= $this->Paginator->last('Last >>', ['class' => 'page-link']) ?>
    </ul>
</nav>
<?php endif; ?>