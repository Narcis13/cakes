<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $newsItem
 * @var array $categories
 * @var array $authors
 */
?>
<?php $this->assign('title', 'Edit News Article'); ?>

<!-- Include TinyMCE for rich text editing -->
<?= $this->element('admin/tinymce', ['selector' => '#content-editor']) ?>

<div class="news edit content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Edit News Article') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> View',
                ['action' => 'view', $newsItem->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
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
                        'label' => ['class' => 'form-label', 'text' => 'URL Slug'],
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
                        'label' => ['class' => 'form-label', 'text' => 'Publish Date'],
                        'value' => $newsItem->publish_date ? $newsItem->publish_date->format('Y-m-d\TH:i') : null
                    ]) ?>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-eye"></i> Views: <?= $this->Number->format($newsItem->views_count) ?>
                        </small>
                    </div>
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
                    <?php if ($newsItem->featured_image): ?>
                    <div class="current-image mb-3">
                        <label class="form-label">Current Image:</label>
                        <img src="<?= $this->Url->build('/img/news/' . $newsItem->featured_image) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= h($newsItem->title) ?>">
                    </div>
                    <?php endif; ?>
                    
                    <?= $this->Form->control('featured_image_file', [
                        'type' => 'file',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => $newsItem->featured_image ? 'Change Image' : 'Upload Image'],
                        'accept' => 'image/*'
                    ]) ?>
                    <div class="form-text">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Recommended size: 1200x630px. Max size: 10MB.
                        </small>
                    </div>
                    
                    <div id="imagePreview" style="display: none;" class="mt-3">
                        <label class="form-label">New Image Preview:</label>
                        <img id="previewImg" class="img-fluid rounded">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body text-muted small">
                    <p class="mb-1"><strong>Article Details:</strong></p>
                    Created: <?= h($newsItem->created->format('M j, Y g:i A')) ?><br>
                    Modified: <?= h($newsItem->modified->format('M j, Y g:i A')) ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <?= $this->Form->button(__('Update Article'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Cancel'), 
                    ['action' => 'view', $newsItem->id], 
                    ['class' => 'btn btn-outline-secondary']
                ) ?>
                <?= $this->Form->postLink(__('Delete'), 
                    ['action' => 'delete', $newsItem->id], 
                    [
                        'confirm' => __('Are you sure you want to delete this article? This action cannot be undone.'),
                        'class' => 'btn btn-outline-danger'
                    ]
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
});
</script>