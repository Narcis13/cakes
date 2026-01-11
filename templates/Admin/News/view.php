<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $newsItem
 */
?>
<?php $this->assign('title', $newsItem->title); ?>

<div class="news view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><?= h($newsItem->title) ?></h3>
            <?php if ($newsItem->is_published): ?>
                <span class="badge bg-success">Publicat</span>
            <?php else: ?>
                <span class="badge bg-warning text-dark">Ciornă</span>
            <?php endif; ?>
            <?php if ($newsItem->news_category): ?>
                <span class="badge bg-info text-dark ms-1"><?= h($newsItem->news_category->name) ?></span>
            <?php endif; ?>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Editează',
                ['action' => 'edit', $newsItem->id],
                ['class' => 'btn btn-primary me-2', 'escape' => false]
            ) ?>
            <?php if ($newsItem->is_published): ?>
                <?= $this->Html->link(
                    '<i class="fas fa-external-link-alt"></i> Vezi live',
                    ['prefix' => false, 'controller' => 'News', 'action' => 'view', $newsItem->slug],
                    ['class' => 'btn btn-info me-2', 'escape' => false, 'target' => '_blank']
                ) ?>
            <?php endif; ?>
            <?= $this->Html->link(
                '<i class="fas fa-list"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?php if ($newsItem->featured_image): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Imagine principală</h5>
                    <img src="<?= $this->Url->build('/img/news/' . $newsItem->featured_image) ?>"
                         class="img-fluid rounded"
                         alt="<?= h($newsItem->title) ?>">
                </div>
            </div>
            <?php endif; ?>

            <?php if ($newsItem->excerpt): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-quote-left"></i> Rezumat</h5>
                </div>
                <div class="card-body">
                    <p class="lead"><?= h($newsItem->excerpt) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Conținut</h5>
                </div>
                <div class="card-body">
                    <?= $this->Purifier->clean($newsItem->content) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informații articol</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%;">Status:</th>
                            <td>
                                <?php if ($newsItem->is_published): ?>
                                    <span class="badge bg-success">Publicat</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Ciornă</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Categorie:</th>
                            <td>
                                <?php if ($newsItem->news_category): ?>
                                    <?= $this->Html->link(
                                        h($newsItem->news_category->name),
                                        ['controller' => 'NewsCategories', 'action' => 'view', $newsItem->news_category->id],
                                        ['class' => 'text-decoration-none']
                                    ) ?>
                                <?php else: ?>
                                    <span class="text-muted">Niciuna</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Autor:</th>
                            <td>
                                <?php if ($newsItem->staff): ?>
                                    <?= $this->Html->link(
                                        h($newsItem->staff->first_name . ' ' . $newsItem->staff->last_name),
                                        ['controller' => 'Staff', 'action' => 'view', $newsItem->staff->id],
                                        ['class' => 'text-decoration-none']
                                    ) ?>
                                    <?php if ($newsItem->staff->title): ?>
                                        <br><small class="text-muted"><?= h($newsItem->staff->title) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Neatribuit</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Vizualizări:</th>
                            <td>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-eye"></i> <?= $this->Number->format($newsItem->views_count) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Slug:</th>
                            <td><code><?= h($newsItem->slug) ?></code></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Detalii publicare</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%;">Publicat:</th>
                            <td>
                                <?php if ($newsItem->publish_date): ?>
                                    <?= h($newsItem->publish_date->format('j M Y H:i')) ?>
                                <?php else: ?>
                                    <span class="text-muted">Nepublicat</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Creat:</th>
                            <td><?= h($newsItem->created->format('j M Y H:i')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modificat:</th>
                            <td><?= h($newsItem->modified->format('j M Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <?= $this->Form->postLink(
                        $newsItem->is_published ? '<i class="fas fa-eye-slash"></i> Anulează publicarea' : '<i class="fas fa-check-circle"></i> Publică',
                        ['action' => 'togglePublished', $newsItem->id],
                        [
                            'class' => 'btn btn-warning w-100 mb-2',
                            'escape' => false,
                            'confirm' => $newsItem->is_published
                                ? __('Sigur doriți să anulați publicarea acestui articol?')
                                : __('Sigur doriți să publicați acest articol?')
                        ]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Șterge articolul',
                        ['action' => 'delete', $newsItem->id],
                        [
                            'class' => 'btn btn-danger w-100',
                            'escape' => false,
                            'confirm' => __('Sigur doriți să ștergeți acest articol? Această acțiune nu poate fi anulată.')
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
