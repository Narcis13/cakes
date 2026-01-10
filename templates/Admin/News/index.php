<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\News> $news
 * @var array $categories
 * @var array $authors
 */
?>
<?php $this->assign('title', 'Gestionare știri'); ?>

<div class="news index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Gestionare știri') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-folder-plus"></i> Categorii',
                ['controller' => 'NewsCategories', 'action' => 'index'],
                ['class' => 'btn btn-secondary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> Articol nou',
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
                        'label' => 'Caută după titlu',
                        'value' => $this->request->getQuery('search'),
                        'class' => 'form-control',
                        'placeholder' => 'Introduceți titlul...'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('category_id', [
                        'label' => 'Categorie',
                        'options' => ['' => 'Toate categoriile'] + $categories,
                        'value' => $this->request->getQuery('category_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('author_id', [
                        'label' => 'Autor',
                        'options' => ['' => 'Toți autorii'] + $authors,
                        'value' => $this->request->getQuery('author_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('is_published', [
                        'label' => 'Status',
                        'options' => ['' => 'Toate', '1' => 'Publicat', '0' => 'Ciornă'],
                        'value' => $this->request->getQuery('is_published'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <?= $this->Form->button('<i class="fas fa-filter"></i> Filtrează', [
                            'type' => 'submit',
                            'class' => 'btn btn-secondary',
                            'escape' => false
                        ]) ?>
                        <?= $this->Html->link('<i class="fas fa-times"></i> Resetează',
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
                                <th>Articol</th>
                                <th><?= $this->Paginator->sort('category_id', 'Categorie') ?></th>
                                <th><?= $this->Paginator->sort('author_id', 'Autor') ?></th>
                                <th><?= $this->Paginator->sort('publish_date', 'Publicat') ?></th>
                                <th><?= $this->Paginator->sort('views_count', 'Vizualizări') ?></th>
                                <th><?= $this->Paginator->sort('is_published', 'Status') ?></th>
                                <th class="actions"><?= __('Acțiuni') ?></th>
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
                                        <small><?= h($newsItem->publish_date->format('j M Y')) ?></small>
                                        <br>
                                        <small class="text-muted"><?= h($newsItem->publish_date->format('H:i')) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Nesetat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-eye"></i> <?= $this->Number->format($newsItem->views_count) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($newsItem->is_published): ?>
                                        <span class="badge bg-success">Publicat</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Ciornă</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <?php if (!$newsItem->is_published): ?>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-eye"></i>',
                                            ['prefix' => false, 'controller' => 'News', 'action' => 'view', $newsItem->slug],
                                            ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'Previzualizează', 'target' => '_blank']
                                        ) ?>
                                    <?php else: ?>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-external-link-alt"></i>',
                                            ['prefix' => false, 'controller' => 'News', 'action' => 'view', $newsItem->slug],
                                            ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'Vezi live', 'target' => '_blank']
                                        ) ?>
                                    <?php endif; ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $newsItem->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Editează']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-check-circle"></i>',
                                        ['action' => 'togglePublished', $newsItem->id],
                                        [
                                            'confirm' => $newsItem->is_published
                                                ? __('Sigur doriți să anulați publicarea „{0}"?', $newsItem->title)
                                                : __('Sigur doriți să publicați „{0}"?', $newsItem->title),
                                            'class' => 'btn btn-sm btn-outline-primary',
                                            'escape' => false,
                                            'title' => $newsItem->is_published ? 'Anulează publicarea' : 'Publică'
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $newsItem->id],
                                        [
                                            'confirm' => __('Sigur doriți să ștergeți „{0}"?', $newsItem->title),
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Șterge'
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
                        <?= $this->Paginator->first('<< ' . __('prima')) ?>
                        <?= $this->Paginator->prev('< ' . __('anterioară')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('următoarea') . ' >') ?>
                        <?= $this->Paginator->last(__('ultima') . ' >>') ?>
                    </ul>
                    <p class="text-muted"><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrare(ări) din {{count}} total')) ?></p>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nu s-au găsit articole de știri</h5>
                    <p class="text-muted">Creați primul articol de știri pentru a începe.</p>
                    <?= $this->Html->link(
                        'Creează articol',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
