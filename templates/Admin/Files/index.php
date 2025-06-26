<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\File> $files
 * @var array $fileTypes
 * @var array $categories
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt"></i> File Manager</h2>
    <?= $this->Html->link(
        '<i class="fas fa-plus"></i> Upload File',
        ['action' => 'add'],
        ['class' => 'btn btn-primary', 'escape' => false]
    ) ?>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3']) ?>
        <div class="col-md-4">
            <?= $this->Form->control('search', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Search',
                'placeholder' => 'Search files...',
                'value' => $this->request->getQuery('search')
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $this->Form->control('type', [
                'type' => 'select',
                'class' => 'form-select',
                'label' => 'File Type',
                'empty' => 'All Types',
                'options' => array_combine(
                    array_column($fileTypes, 'file_type'),
                    array_map('ucfirst', array_column($fileTypes, 'file_type'))
                ),
                'value' => $this->request->getQuery('type')
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $this->Form->control('category', [
                'type' => 'select',
                'class' => 'form-select',
                'label' => 'Category',
                'empty' => 'All Categories',
                'options' => array_combine(
                    array_column($categories, 'category'),
                    array_column($categories, 'category')
                ),
                'value' => $this->request->getQuery('category')
            ]) ?>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <?= $this->Form->button(
                '<i class="fas fa-search"></i> Filter',
                ['type' => 'submit', 'class' => 'btn btn-outline-primary', 'escape' => false]
            ) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Files Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($files) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><?= $this->Paginator->sort('original_name', 'File Name') ?></th>
                        <th><?= $this->Paginator->sort('file_type', 'Type') ?></th>
                        <th><?= $this->Paginator->sort('category', 'Category') ?></th>
                        <th><?= $this->Paginator->sort('file_size', 'Size') ?></th>
                        <th><?= $this->Paginator->sort('download_count', 'Downloads') ?></th>
                        <th><?= $this->Paginator->sort('is_public', 'Visibility') ?></th>
                        <th><?= $this->Paginator->sort('created', 'Uploaded') ?></th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="<?= $this->element('file_icon', ['file_type' => $file->file_type]) ?> me-2"></i>
                                <div>
                                    <strong><?= h($file->original_name) ?></strong>
                                    <?php if ($file->description): ?>
                                        <br><small class="text-muted"><?= h($file->description) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?= $this->element('file_type_color', ['file_type' => $file->file_type]) ?>">
                                <?= h(ucfirst($file->file_type)) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($file->category): ?>
                                <span class="badge bg-secondary"><?= h($file->category) ?></span>
                            <?php else: ?>
                                <span class="text-muted">No category</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $this->Number->toReadableSize($file->file_size) ?></td>
                        <td>
                            <span class="badge bg-info"><?= $this->Number->format($file->download_count) ?></span>
                        </td>
                        <td>
                            <?php if ($file->is_public): ?>
                                <span class="badge bg-success">Public</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Private</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= h($file->created->format('M j, Y')) ?>
                            <?php if ($file->user): ?>
                                <br><small class="text-muted">by <?= h($file->user->email) ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <?= $this->Html->link(
                                    '<i class="fas fa-download"></i>',
                                    ['action' => 'download', $file->id],
                                    ['class' => 'btn btn-sm btn-outline-success', 'escape' => false, 'title' => 'Download']
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-eye"></i>',
                                    ['action' => 'view', $file->id],
                                    ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'View']
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    ['action' => 'edit', $file->id],
                                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Edit']
                                ) ?>
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    ['action' => 'delete', $file->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete "{0}"?', $file->original_name),
                                        'class' => 'btn btn-sm btn-outline-danger',
                                        'escape' => false,
                                        'title' => 'Delete'
                                    ]
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-copy"></i>',
                                    'javascript:void(0)',
                                    [
                                        'class' => 'btn btn-sm btn-outline-secondary copy-url',
                                        'escape' => false,
                                        'title' => 'Copy URL',
                                        'data-url' => $this->Url->build($file->file_url, ['fullBase' => true])
                                    ]
                                ) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No files found</h5>
            <p class="text-muted">Upload your first file to get started.</p>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> Upload File',
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination -->
<?php if ($this->Paginator->total() > $this->Paginator->param('perPage')): ?>
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<< First', ['class' => 'page-link']) ?>
        <?= $this->Paginator->prev('< Previous', ['class' => 'page-link']) ?>
        <?= $this->Paginator->numbers(['class' => 'page-link']) ?>
        <?= $this->Paginator->next('Next >', ['class' => 'page-link']) ?>
        <?= $this->Paginator->last('Last >>', ['class' => 'page-link']) ?>
    </ul>
</nav>
<?php endif; ?>

<!-- Copy URL JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.copy-url').forEach(function(button) {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(function() {
                // Show toast or alert
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '1055';
                toast.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong class="me-auto">Success</strong>
                        </div>
                        <div class="toast-body">
                            URL copied to clipboard!
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 3000);
            });
        });
    });
});
</script>
