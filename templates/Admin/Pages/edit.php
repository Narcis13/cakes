<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
?>
<?php $this->assign('title', 'Edit Page: ' . $page->title); ?>

<div class="pages form content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Edit Page: {0}', h($page->title)) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> View Page',
                '/' . $page->slug,
                ['class' => 'btn btn-outline-info', 'escape' => false, 'target' => '_blank']
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to Pages',
                ['action' => 'index'],
                ['class' => 'btn btn-outline-secondary ms-2', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Page Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Details</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->create($page) ?>
                        <div class="mb-3">
                            <?= $this->Form->control('title', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('slug', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('content', [
                                'type' => 'textarea',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Page Content (Optional)'],
                                'rows' => 4,
                                'help' => 'This content appears before the components'
                            ]) ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <?= $this->Form->control('meta_description', [
                                        'class' => 'form-control',
                                        'label' => ['class' => 'form-label'],
                                        'help' => 'SEO meta description'
                                    ]) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <?= $this->Form->control('template', [
                                        'class' => 'form-control',
                                        'label' => ['class' => 'form-label'],
                                        'empty' => 'Default Template'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <?= $this->Form->checkbox('is_published', [
                                    'class' => 'form-check-input'
                                ]) ?>
                                <?= $this->Form->label('is_published', 'Published', [
                                    'class' => 'form-check-label'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <?= $this->Form->button('Update Page', [
                                'class' => 'btn btn-primary'
                            ]) ?>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>

            <!-- Page Components -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Page Components</h5>
                </div>
                <div class="card-body">
                    <div id="components-container" class="sortable-components">
                        <?php if (!empty($page->page_components)): ?>
                            <?php foreach ($page->page_components as $component): ?>
                                <div class="component-item mb-3 p-3 border rounded" data-component-id="<?= $component->id ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-secondary me-2"><?= ucfirst($component->type) ?></span>
                                                <span class="component-handle text-muted" style="cursor: move;">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </span>
                                                <?php if ($component->title): ?>
                                                    <strong class="ms-2"><?= h($component->title) ?></strong>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($component->type === 'html'): ?>
                                                <div class="component-content"><?= nl2br(h($component->content)) ?></div>
                                            <?php elseif ($component->type === 'image'): ?>
                                                <div class="component-content">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <img src="<?= h($component->url) ?>" 
                                                             alt="<?= h($component->alt_text ?: $component->title) ?>" 
                                                             style="max-width: 100px; max-height: 60px; object-fit: cover;" 
                                                             class="rounded me-2">
                                                        <div>
                                                            <strong>Source:</strong> 
                                                            <?php if ($component->image_type === 'upload'): ?>
                                                                <span class="badge bg-success">Uploaded</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-info">URL</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <strong>URL:</strong> <?= h($component->url) ?><br>
                                                    <?php if ($component->alt_text): ?>
                                                        <strong>Alt Text:</strong> <?= h($component->alt_text) ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php elseif ($component->type === 'link'): ?>
                                                <div class="component-content">
                                                    <strong>URL:</strong> <?= h($component->url) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-component" 
                                                    data-component-id="<?= $component->id ?>"
                                                    data-bs-toggle="modal" data-bs-target="#editComponentModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?= $this->Form->postLink(
                                                '<i class="fas fa-trash"></i>',
                                                ['action' => 'deleteComponent', $component->id],
                                                [
                                                    'confirm' => 'Are you sure you want to delete this component?',
                                                    'class' => 'btn btn-sm btn-outline-danger',
                                                    'escape' => false
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-puzzle-piece fa-2x mb-3"></i>
                                <p>No components added yet. Start building your page by adding components.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Add Component -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Component</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->create(null, [
                        'url' => ['action' => 'addComponent', $page->id],
                        'type' => 'file'
                    ]) ?>
                        <div class="mb-3">
                            <?= $this->Form->control('type', [
                                'type' => 'select',
                                'options' => [
                                    'html' => 'HTML Paragraph',
                                    'image' => 'Image',
                                    'link' => 'Link'
                                ],
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'empty' => 'Select component type...',
                                'id' => 'component-type'
                            ]) ?>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('title', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'placeholder' => 'Component title (optional)'
                            ]) ?>
                        </div>
                        
                        <!-- HTML Content -->
                        <div class="component-field mb-3" id="html-fields" style="display:none;">
                            <?= $this->Form->control('content', [
                                'type' => 'textarea',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'HTML Content'],
                                'rows' => 4
                            ]) ?>
                        </div>
                        
                        <!-- Image Fields -->
                        <div class="component-field" id="image-fields" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label">Choose Image Source</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="image_source" id="image_url" value="url" checked>
                                    <label class="form-check-label" for="image_url">
                                        Image URL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="image_source" id="image_upload" value="upload">
                                    <label class="form-check-label" for="image_upload">
                                        Upload Image
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="url-input">
                                <?= $this->Form->control('url', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Image URL'],
                                    'placeholder' => 'https://example.com/image.jpg'
                                ]) ?>
                            </div>
                            
                            <div class="mb-3" id="file-input" style="display:none;">
                                <?= $this->Form->control('image_file', [
                                    'type' => 'file',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Upload Image'],
                                    'accept' => 'image/*'
                                ]) ?>
                                <div class="form-text">Maximum file size: 5MB. Supported formats: JPEG, PNG, GIF, WebP</div>
                            </div>
                            
                            <div class="mb-3">
                                <?= $this->Form->control('alt_text', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'placeholder' => 'Alternative text for accessibility'
                                ]) ?>
                            </div>
                        </div>
                        
                        <!-- Link Fields -->
                        <div class="component-field" id="link-fields" style="display:none;">
                            <div class="mb-3">
                                <?= $this->Form->control('url', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Link URL'],
                                    'placeholder' => 'https://example.com'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('css_class', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'placeholder' => 'Custom CSS classes (optional)'
                            ]) ?>
                        </div>
                        
                        <?= $this->Form->button('Add Component', [
                            'class' => 'btn btn-success w-100'
                        ]) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Component Modal -->
<div class="modal fade" id="editComponentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Component</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Component type selector
    const typeSelect = document.getElementById('component-type');
    const componentFields = document.querySelectorAll('.component-field');
    
    typeSelect.addEventListener('change', function() {
        // Hide all fields
        componentFields.forEach(field => field.style.display = 'none');
        
        // Show relevant fields
        const selectedType = this.value;
        if (selectedType) {
            const fieldsToShow = document.getElementById(selectedType + '-fields');
            if (fieldsToShow) {
                fieldsToShow.style.display = 'block';
            }
        }
    });
    
    // Image source selector
    const imageSourceRadios = document.querySelectorAll('input[name="image_source"]');
    const urlInput = document.getElementById('url-input');
    const fileInput = document.getElementById('file-input');
    
    imageSourceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'url') {
                urlInput.style.display = 'block';
                fileInput.style.display = 'none';
                // Clear file input
                const fileField = document.querySelector('input[type="file"]');
                if (fileField) fileField.value = '';
            } else {
                urlInput.style.display = 'none';
                fileInput.style.display = 'block';
                // Clear URL input
                const urlField = document.getElementById('url');
                if (urlField) urlField.value = '';
            }
        });
    });
    
    // Make components sortable
    const container = document.getElementById('components-container');
    if (container) {
        new Sortable(container, {
            handle: '.component-handle',
            animation: 150,
            onEnd: function(evt) {
                const componentIds = Array.from(container.children).map(item => 
                    item.getAttribute('data-component-id')
                );
                
                // Send AJAX request to update order
                fetch('<?= $this->Url->build(['action' => 'reorderComponents', $page->id]) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                    },
                    body: JSON.stringify({
                        component_ids: componentIds
                    })
                });
            }
        });
    }
    
    // Edit component functionality
    document.querySelectorAll('.edit-component').forEach(button => {
        button.addEventListener('click', function() {
            const componentId = this.getAttribute('data-component-id');
            // Here you would load the component data and populate the modal
            // For now, we'll just show a placeholder
            const modalBody = document.querySelector('#editComponentModal .modal-body');
            modalBody.innerHTML = '<p>Edit component functionality coming soon...</p>';
        });
    });
});
</script>
