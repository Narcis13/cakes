<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NewsCategory $newsCategory
 */
?>
<?php $this->assign('title', 'Edit News Category'); ?>

<div class="newsCategories edit content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Edit News Category: {0}', h($newsCategory->name)) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> View',
                ['action' => 'view', $newsCategory->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?= $this->Form->create($newsCategory) ?>
                    
                    <fieldset>
                        <legend><?= __('Category Information') ?></legend>
                        
                        <?= $this->Form->control('name', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'required' => true,
                            'placeholder' => 'e.g., Hospital Updates, Health Tips, Events'
                        ]) ?>
                        
                        <?= $this->Form->control('slug', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'URL Slug'],
                            'placeholder' => 'category-url-slug'
                        ]) ?>
                        
                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'rows' => 4,
                            'placeholder' => 'Brief description of this category (optional)...'
                        ]) ?>
                    </fieldset>
                    
                    <div class="form-actions mt-4">
                        <?= $this->Form->button(__('Update Category'), [
                            'class' => 'btn btn-primary'
                        ]) ?>
                        <?= $this->Html->link(__('Cancel'), 
                            ['action' => 'view', $newsCategory->id], 
                            ['class' => 'btn btn-outline-secondary ms-2']
                        ) ?>
                        <?= $this->Form->postLink(__('Delete'), 
                            ['action' => 'delete', $newsCategory->id], 
                            [
                                'confirm' => __('Are you sure you want to delete this category? This action cannot be undone.'),
                                'class' => 'btn btn-outline-danger ms-2'
                            ]
                        ) ?>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Category Details</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Created:</strong><br>
                    <small class="text-muted"><?= h($newsCategory->created->format('M j, Y g:i A')) ?></small></p>
                    
                    <p class="mb-0"><strong>Last Modified:</strong><br>
                    <small class="text-muted"><?= h($newsCategory->modified->format('M j, Y g:i A')) ?></small></p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Important:</strong> Changing the slug will affect existing URLs to this category.
                    </div>
                    
                    <p class="mb-2"><strong>Best Practices:</strong></p>
                    <ul class="small mb-0">
                        <li>Keep category names clear and concise</li>
                        <li>Update descriptions to help authors choose the right category</li>
                        <li>Avoid changing slugs if articles are already published</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name only if slug is empty
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (nameInput && slugInput) {
        nameInput.addEventListener('blur', function() {
            if (!slugInput.value && nameInput.value) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    }
});
</script>