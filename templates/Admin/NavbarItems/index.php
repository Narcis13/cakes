<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\NavbarItem> $navbarItems
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-bars"></i> Elemente meniu</h2>
    <?= $this->Html->link(
        '<i class="fas fa-plus"></i> Adaugă element nou',
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
                        <th><?= $this->Paginator->sort('title', 'Titlu') ?></th>
                        <th><?= $this->Paginator->sort('url', 'URL') ?></th>
                        <th><?= $this->Paginator->sort('parent_id', 'Părinte') ?></th>
                        <th><?= $this->Paginator->sort('sort_order', 'Ordine') ?></th>
                        <th><?= $this->Paginator->sort('is_active', 'Activ') ?></th>
                        <th class="text-center">Acțiuni</th>
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
                                <span class="text-muted">Fără URL</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $navbarItem->hasValue('parent_navbar_item') ? h($navbarItem->parent_navbar_item->title) : '<span class="text-muted">Nivel principal</span>' ?>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= $this->Number->format($navbarItem->sort_order) ?></span>
                        </td>
                        <td>
                            <?php if ($navbarItem->is_active): ?>
                                <span class="badge bg-success">Activ</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactiv</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <?= $this->Html->link(
                                    '<i class="fas fa-eye"></i>',
                                    ['action' => 'view', $navbarItem->id],
                                    ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'Vizualizează']
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    ['action' => 'edit', $navbarItem->id],
                                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Editează']
                                ) ?>
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    ['action' => 'delete', $navbarItem->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Sigur doriți să ștergeți "{0}"?', $navbarItem->title),
                                        'class' => 'btn btn-sm btn-outline-danger',
                                        'escape' => false,
                                        'title' => 'Șterge'
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

<div class="d-flex justify-content-between align-items-center mt-3">
    <p class="text-muted mb-0">
        <?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrare(ări) din {{count}} total')) ?>
    </p>
    <nav aria-label="Navigare pagini">
        <ul class="pagination mb-0">
            <?= $this->Paginator->first('<< Prima') ?>
            <?= $this->Paginator->prev('< Anterioară') ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('Următoarea >') ?>
            <?= $this->Paginator->last('Ultima >>') ?>
        </ul>
    </nav>
</div>
