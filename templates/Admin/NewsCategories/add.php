<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NewsCategory $newsCategory
 */
?>
<?php $this->assign('title', 'Add News Category'); ?>

<div class="newsCategories add content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Add News Category') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
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
                            'label' => ['class' => 'form-label', 'text' => 'URL Slug (leave empty to auto-generate)'],
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
                    
                    <div class="mt-4">
                        <?= $this->Form->button(__('Save Category'), [
                            'class' => 'btn btn-primary'
                        ]) ?>
                        <?= $this->Html->link(__('Cancel'), 
                            ['action' => 'index'], 
                            ['class' => 'btn btn-outline-secondary ms-2']
                        ) ?>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Category Name:</strong></p>
                    <ul class="small">
                        <li>Use clear, descriptive names</li>
                        <li>Keep it short and memorable</li>
                        <li>Examples: "Hospital News", "Health Tips", "Events"</li>
                    </ul>
                    
                    <p class="mb-2 mt-3"><strong>URL Slug:</strong></p>
                    <ul class="small mb-0">
                        <li>Will be auto-generated from the name</li>
                        <li>Used in URLs for category pages</li>
                        <li>Should be lowercase with hyphens</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
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