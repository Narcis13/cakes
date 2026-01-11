<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
?>
<?php $this->assign('title', $page->title); ?>

<div class="pages view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= h($page->title) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> Vezi live',
                '/' . $page->slug,
                ['class' => 'btn btn-outline-info', 'escape' => false, 'target' => '_blank']
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Editează',
                ['action' => 'edit', $page->id],
                ['class' => 'btn btn-primary ms-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la pagini',
                ['action' => 'index'],
                ['class' => 'btn btn-outline-secondary ms-2', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php if ($page->content): ?>
                        <div class="page-content mb-4">
                            <?= nl2br(h($page->content)) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($page->page_components)): ?>
                        <div class="page-components">
                            <?php foreach ($page->page_components as $component): ?>
                                <?php if ($component->is_active): ?>
                                    <div class="component component-<?= h($component->type) ?> mb-4 <?= h($component->css_class) ?>">
                                        <?php if ($component->title): ?>
                                            <h4><?= h($component->title) ?></h4>
                                        <?php endif; ?>

                                        <?php if ($component->type === 'html'): ?>
                                            <div class="component-content">
                                                <?= nl2br(h($component->content)) ?>
                                            </div>
                                        <?php elseif ($component->type === 'image'): ?>
                                            <div class="component-content">
                                                <img src="<?= h($component->url) ?>"
                                                     alt="<?= h($component->alt_text ?: $component->title) ?>"
                                                     class="img-fluid">
                                            </div>
                                        <?php elseif ($component->type === 'link'): ?>
                                            <div class="component-content">
                                                <a href="<?= h($component->url) ?>" class="btn btn-primary">
                                                    <?= h($component->title ?: $component->url) ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informații pagină</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7">
                            <?php if ($page->is_published): ?>
                                <span class="badge bg-success">Publicat</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ciornă</span>
                            <?php endif; ?>
                        </dd>

                        <dt class="col-sm-5">Slug:</dt>
                        <dd class="col-sm-7"><code><?= h($page->slug) ?></code></dd>

                        <?php if ($page->template): ?>
                            <dt class="col-sm-5">Șablon:</dt>
                            <dd class="col-sm-7"><?= h($page->template) ?></dd>
                        <?php endif; ?>

                        <?php if ($page->meta_description): ?>
                            <dt class="col-sm-5">Descriere meta:</dt>
                            <dd class="col-sm-7"><?= h($page->meta_description) ?></dd>
                        <?php endif; ?>

                        <dt class="col-sm-5">Creat:</dt>
                        <dd class="col-sm-7"><?= h($page->created->format('j M Y H:i')) ?></dd>

                        <dt class="col-sm-5">Modificat:</dt>
                        <dd class="col-sm-7"><?= h($page->modified->format('j M Y H:i')) ?></dd>

                        <dt class="col-sm-5">Componente:</dt>
                        <dd class="col-sm-7"><?= count($page->page_components) ?></dd>
                    </dl>
                </div>
            </div>

            <?php if (!empty($page->page_components)): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Componente</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($page->page_components as $component): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-secondary me-2"><?= ucfirst($component->type) ?></span>
                                            <?php if ($component->title): ?>
                                                <?= h($component->title) ?>
                                            <?php else: ?>
                                                <em class="text-muted">Fără titlu</em>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <?php if ($component->is_active): ?>
                                                <span class="badge bg-success">Activ</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactiv</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
