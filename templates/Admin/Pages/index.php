<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Page> $pages
 */
?>
<?php $this->assign('title', 'Pagini'); ?>

<div class="pages index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Pagini') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Pagină nouă',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (!$pages->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('title', 'Titlu') ?></th>
                                <th><?= $this->Paginator->sort('slug', 'Slug') ?></th>
                                <th><?= $this->Paginator->sort('is_published', 'Status') ?></th>
                                <th><?= $this->Paginator->sort('created', 'Creat') ?></th>
                                <th class="actions"><?= __('Acțiuni') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pages as $page): ?>
                            <tr>
                                <td>
                                    <?= $this->Html->link(
                                        h($page->title),
                                        ['action' => 'view', $page->id]
                                    ) ?>
                                </td>
                                <td>
                                    <code><?= h($page->slug) ?></code>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-external-link-alt"></i>',
                                        '/' . $page->slug,
                                        ['target' => '_blank', 'escape' => false, 'class' => 'ms-2 text-muted']
                                    ) ?>
                                </td>
                                <td>
                                    <?php if ($page->is_published): ?>
                                        <span class="badge bg-success">Publicat</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ciornă</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($page->created->format('j M Y H:i')) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $page->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Vizualizează']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $page->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Editează']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $page->id],
                                        [
                                            'confirm' => __('Sigur doriți să ștergeți „{0}"?', $page->title),
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
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nu s-au găsit pagini</h5>
                    <p class="text-muted">Creați prima pagină pentru a începe.</p>
                    <?= $this->Html->link(
                        'Creează pagină',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
