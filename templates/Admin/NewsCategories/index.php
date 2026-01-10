<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\NewsCategory> $newsCategories
 */
?>
<?php $this->assign('title', 'Categorii știri'); ?>

<div class="newsCategories index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Categorii știri') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-newspaper"></i> Articole știri',
                ['controller' => 'News', 'action' => 'index'],
                ['class' => 'btn btn-secondary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> Categorie nouă',
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
                                <th><?= $this->Paginator->sort('name', 'Nume categorie') ?></th>
                                <th><?= $this->Paginator->sort('slug', 'Slug') ?></th>
                                <th>Articole</th>
                                <th><?= $this->Paginator->sort('created', 'Creat') ?></th>
                                <th class="actions"><?= __('Acțiuni') ?></th>
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
                                        <?= isset($newsCategory->news_count) ? $newsCategory->news_count : 0 ?> articole
                                    </span>
                                </td>
                                <td><?= h($newsCategory->created->format('j M Y')) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $newsCategory->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Vizualizează']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $newsCategory->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Editează']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $newsCategory->id],
                                        [
                                            'confirm' => __('Sigur doriți să ștergeți „{0}"?', $newsCategory->name),
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
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nu s-au găsit categorii de știri</h5>
                    <p class="text-muted">Creați prima categorie de știri pentru a organiza articolele.</p>
                    <?= $this->Html->link(
                        'Creează categorie',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
