<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $newsItem
 * @var array $categories
 * @var array $authors
 */
?>
<?php $this->assign('title', 'Create News Article'); ?>

<!-- Include TinyMCE for rich text editing -->
<?= $this->element('admin/tinymce', ['selector' => '#content-editor']) ?>

<div class="news add content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Create News Article') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
    </div>

    <?php if ($newsItem->getErrors()): ?>
    <div class="alert alert-danger">
        <h5>Please correct the following errors:</h5>
        <ul class="mb-0">
            <?php foreach ($newsItem->getErrors() as $field => $errors): ?>
                <?php foreach ($errors as $error): ?>
                    <li><?= h($field) ?>: <?= h($error) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?= $this->Form->create($newsItem, ['type' => 'file']) ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Article Details</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('title', [
                        'class' => 'form-control form-control-lg',
                        'label' => ['class' => 'form-label'],
                        'required' => true,
                        'placeholder' => 'Enter article title...'
                    ]) ?>
                    
                    <?= $this->Form->control('slug', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'URL Slug (leave empty to auto-generate)'],
                        'placeholder' => 'article-url-slug'
                    ]) ?>
                    
                    <?= $this->Form->control('excerpt', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Excerpt / Summary'],
                        'rows' => 3,
                        'placeholder' => 'Brief summary of the article (optional)...'
                    ]) ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Content</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('content', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'content-editor',
                        'rows' => 15,
                        'required' => false
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Publishing</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('is_published', [
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'label' => [
                            'class' => 'form-check-label',
                            'text' => 'Publish this article'
                        ],
                        'templates' => [
                            'checkboxWrapper' => '<div class="form-check mb-3">{{label}}</div>',
                            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                        ]
                    ]) ?>
                    
                    <?= $this->Form->control('publish_date', [
                        'type' => 'datetime-local',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Publish Date (leave empty for immediate)'],
                        'value' => null
                    ]) ?>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Categorization</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('category_id', [
                        'type' => 'select',
                        'options' => $categories,
                        'empty' => 'Select Category',
                        'class' => 'form-select mb-3',
                        'label' => ['class' => 'form-label', 'text' => 'Category']
                    ]) ?>
                    
                    <?= $this->Form->control('author_id', [
                        'type' => 'select',
                        'options' => $authors,
                        'empty' => 'Select Author',
                        'class' => 'form-select',
                        'label' => ['class' => 'form-label', 'text' => 'Author']
                    ]) ?>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Featured Image</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('featured_image_file', [
                        'type' => 'file',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Upload Image'],
                        'accept' => 'image/*'
                    ]) ?>
                    <div class="form-text">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Recommended size: 1200x630px. Max size: 10MB.
                        </small>
                    </div>
                    
                    <div id="imagePreview" style="display: none;" class="mt-3">
                        <label class="form-label">Preview:</label>
                        <img id="previewImg" class="img-fluid rounded">
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <?= $this->Form->button(__('Save Article'), [
                    'class' => 'btn btn-primary',
                    'id' => 'submit-button'
                ]) ?>
                <?= $this->Html->link(__('Cancel'), 
                    ['action' => 'index'], 
                    ['class' => 'btn btn-outline-secondary']
                ) ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<script>
// Image preview
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="featured_image_file"]');
    const previewDiv = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewDiv.style.display = 'none';
            }
        });
    }

    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value && titleInput.value) {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    }

    // Form submission handler
    const form = document.querySelector('form');
    const submitButton = document.getElementById('submit-button');
    
    if (form && submitButton) {
        form.addEventListener('submit', function(e) {
            // Save TinyMCE content back to textarea
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
        });
    }
});
</script>