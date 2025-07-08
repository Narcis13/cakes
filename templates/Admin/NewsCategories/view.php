<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NewsCategory $newsCategory
 * @var int $newsCount
 */
?>
<?php $this->assign('title', $newsCategory->name); ?>

<div class="newsCategories view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= h($newsCategory->name) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Edit',
                ['action' => 'edit', $newsCategory->id],
                ['class' => 'btn btn-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-list"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Category Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 35%;">Name:</th>
                            <td><?= h($newsCategory->name) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Slug:</th>
                            <td><code><?= h($newsCategory->slug) ?></code></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Articles:</th>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= $newsCount ?> articles
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Created:</th>
                            <td><?= h($newsCategory->created->format('M j, Y g:i A')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modified:</th>
                            <td><?= h($newsCategory->modified->format('M j, Y g:i A')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if ($newsCategory->description): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-align-left"></i> Description</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($newsCategory->description)) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-newspaper"></i> Recent Articles in this Category</h5>
                    <?= $this->Html->link(
                        'View All',
                        ['controller' => 'News', 'action' => 'index', '?' => ['category_id' => $newsCategory->id]],
                        ['class' => 'btn btn-sm btn-outline-primary']
                    ) ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($newsCategory->news)): ?>
                        <div class="list-group">
                            <?php foreach ($newsCategory->news as $article): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <?= $this->Html->link(
                                            h($article->title),
                                            ['controller' => 'News', 'action' => 'view', $article->id],
                                            ['class' => 'fw-bold text-decoration-none']
                                        ) ?>
                                        <?php if ($article->excerpt): ?>
                                            <p class="text-muted mb-1 small"><?= $this->Text->truncate(h($article->excerpt), 100) ?></p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            <?php if ($article->publish_date): ?>
                                                <i class="fas fa-calendar"></i> <?= h($article->publish_date->format('M j, Y')) ?>
                                            <?php else: ?>
                                                <i class="fas fa-calendar"></i> <?= h($article->created->format('M j, Y')) ?>
                                            <?php endif; ?>
                                            &nbsp;|&nbsp;
                                            <i class="fas fa-eye"></i> <?= $this->Number->format($article->views_count) ?> views
                                        </small>
                                    </div>
                                    <div class="ms-3">
                                        <?php if ($article->is_published): ?>
                                            <span class="badge bg-success">Published</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <small>Showing up to 10 most recent articles</small>
                        </p>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-3"></i>
                            <p>No articles in this category yet</p>
                            <?= $this->Html->link(
                                'Create First Article',
                                ['controller' => 'News', 'action' => 'add', '?' => ['category_id' => $newsCategory->id]],
                                ['class' => 'btn btn-sm btn-primary']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>