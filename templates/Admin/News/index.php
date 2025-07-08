<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\News> $news
 * @var array $categories
 * @var array $authors
 */
?>
<?php $this->assign('title', 'News Management'); ?>

<div class="news index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('News Management') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-folder-plus"></i> Categories',
                ['controller' => 'NewsCategories', 'action' => 'index'],
                ['class' => 'btn btn-secondary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> New Article',
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
                <div class="col-md-3">
                    <?= $this->Form->control('search', [
                        'label' => 'Search by Title',
                        'value' => $this->request->getQuery('search'),
                        'class' => 'form-control',
                        'placeholder' => 'Enter title...'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('category_id', [
                        'label' => 'Category',
                        'options' => ['' => 'All Categories'] + $categories,
                        'value' => $this->request->getQuery('category_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('author_id', [
                        'label' => 'Author',
                        'options' => ['' => 'All Authors'] + $authors,
                        'value' => $this->request->getQuery('author_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('is_published', [
                        'label' => 'Status',
                        'options' => ['' => 'All', '1' => 'Published', '0' => 'Draft'],
                        'value' => $this->request->getQuery('is_published'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-3">
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
            <?php if (!$news->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th><?= $this->Paginator->sort('category_id', 'Category') ?></th>
                                <th><?= $this->Paginator->sort('author_id', 'Author') ?></th>
                                <th><?= $this->Paginator->sort('publish_date', 'Published') ?></th>
                                <th><?= $this->Paginator->sort('views_count', 'Views') ?></th>
                                <th><?= $this->Paginator->sort('is_published', 'Status') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($news as $newsItem): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($newsItem->featured_image): ?>
                                            <img src="<?= $this->Url->build('/img/news/' . $newsItem->featured_image) ?>" 
                                                 class="rounded me-2" 
                                                 style="width: 60px; height: 40px; object-fit: cover;" 
                                                 alt="<?= h($newsItem->title) ?>">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center text-white" 
                                                 style="width: 60px; height: 40px; font-size: 12px;">
                                                <i class="fas fa-newspaper"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <?= $this->Html->link(
                                                h($newsItem->title),
                                                ['action' => 'view', $newsItem->id],
                                                ['class' => 'fw-bold text-decoration-none d-block']
                                            ) ?>
                                            <?php if ($newsItem->excerpt): ?>
                                                <small class="text-muted"><?= $this->Text->truncate(h($newsItem->excerpt), 60) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($newsItem->news_category): ?>
                                        <span class="badge bg-info text-dark">
                                            <?= h($newsItem->news_category->name) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($newsItem->staff): ?>
                                        <small>
                                            <i class="fas fa-user"></i> 
                                            <?= h($newsItem->staff->first_name . ' ' . $newsItem->staff->last_name) ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($newsItem->publish_date): ?>
                                        <small><?= h($newsItem->publish_date->format('M j, Y')) ?></small>
                                        <br>
                                        <small class="text-muted"><?= h($newsItem->publish_date->format('g:i A')) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Not set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-eye"></i> <?= $this->Number->format($newsItem->views_count) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($newsItem->is_published): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <?php if (!$newsItem->is_published): ?>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-eye"></i>',
                                            ['prefix' => false, 'controller' => 'News', 'action' => 'view', $newsItem->slug],
                                            ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'Preview', 'target' => '_blank']
                                        ) ?>
                                    <?php else: ?>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-external-link-alt"></i>',
                                            ['prefix' => false, 'controller' => 'News', 'action' => 'view', $newsItem->slug],
                                            ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'View Live', 'target' => '_blank']
                                        ) ?>
                                    <?php endif; ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $newsItem->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Edit']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-check-circle"></i>',
                                        ['action' => 'togglePublished', $newsItem->id],
                                        [
                                            'confirm' => $newsItem->is_published 
                                                ? __('Are you sure you want to unpublish "{0}"?', $newsItem->title)
                                                : __('Are you sure you want to publish "{0}"?', $newsItem->title),
                                            'class' => 'btn btn-sm btn-outline-primary',
                                            'escape' => false,
                                            'title' => $newsItem->is_published ? 'Unpublish' : 'Publish'
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $newsItem->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete "{0}"?', $newsItem->title),
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
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No news articles found</h5>
                    <p class="text-muted">Create your first news article to get started.</p>
                    <?= $this->Html->link(
                        'Create Article',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>