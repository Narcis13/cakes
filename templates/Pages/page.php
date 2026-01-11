<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
?>

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header mb-4">
                    <h1><?= h($page->title) ?></h1>
                </div>
                
                <?php if ($page->content): ?>
                    <div class="page-intro mb-4">
                        <?= nl2br(h($page->content)) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($page->page_components)): ?>
                    <div class="page-components">
                        <?php foreach ($page->page_components as $component): ?>
                            <div class="component component-<?= h($component->type) ?> mb-4 <?= h($component->css_class) ?>">
                                <?php if ($component->title): ?>
                                    <h3 class="component-title"><?= h($component->title) ?></h3>
                                <?php endif; ?>
                                
                                <div class="component-content">
                                    <?php if ($component->type === 'html'): ?>
                                        <?= $this->Purifier->clean($component->content) ?>
                                    <?php elseif ($component->type === 'image'): ?>
                                        <div class="text-center">
                                            <img src="<?= h($component->url) ?>" 
                                                 alt="<?= h($component->alt_text ?: $component->title) ?>" 
                                                 class="img-fluid rounded">
                                        </div>
                                    <?php elseif ($component->type === 'link'): ?>
                                        <div class="text-center">
                                            <a href="<?= h($component->url) ?>" class="btn btn-primary btn-lg">
                                                <?= h($component->button_caption ?: ($component->title ?: $component->url)) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.page-content {
    padding: 2rem 0;
}

.page-header h1 {
    color: #2c3e50;
    border-bottom: 3px solid #3498db;
    padding-bottom: 1rem;
}

.component {
    padding: 1.5rem;
    background: #ffffff;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.component-title {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.component-content {
    line-height: 1.6;
}
</style>
