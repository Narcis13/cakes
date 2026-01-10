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
                '<i class="fas fa-edit"></i> Editează',
                ['action' => 'edit', $newsCategory->id],
                ['class' => 'btn btn-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-list"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informații categorie</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 35%;">Nume:</th>
                            <td><?= h($newsCategory->name) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Slug:</th>
                            <td><code><?= h($newsCategory->slug) ?></code></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Articole:</th>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= $newsCount ?> articole
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Creat:</th>
                            <td><?= h($newsCategory->created->format('j M Y H:i')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modificat:</th>
                            <td><?= h($newsCategory->modified->format('j M Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if ($newsCategory->description): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-align-left"></i> Descriere</h5>
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
                    <h5 class="mb-0"><i class="fas fa-newspaper"></i> Articole recente din această categorie</h5>
                    <?= $this->Html->link(
                        'Vezi toate',
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
                                                <i class="fas fa-calendar"></i> <?= h($article->publish_date->format('j M Y')) ?>
                                            <?php else: ?>
                                                <i class="fas fa-calendar"></i> <?= h($article->created->format('j M Y')) ?>
                                            <?php endif; ?>
                                            &nbsp;|&nbsp;
                                            <i class="fas fa-eye"></i> <?= $this->Number->format($article->views_count) ?> vizualizări
                                        </small>
                                    </div>
                                    <div class="ms-3">
                                        <?php if ($article->is_published): ?>
                                            <span class="badge bg-success">Publicat</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Ciornă</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <small>Se afișează până la 10 cele mai recente articole</small>
                        </p>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-3"></i>
                            <p>Nu există articole în această categorie încă</p>
                            <?= $this->Html->link(
                                'Creează primul articol',
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
