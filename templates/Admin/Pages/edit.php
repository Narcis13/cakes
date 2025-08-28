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
                                                    <strong>URL:</strong> <?= h($component->url) ?><br>
                                                    <?php if ($component->button_caption): ?>
                                                        <strong>Button Caption:</strong> <?= h($component->button_caption) ?>
                                                    <?php endif; ?>
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
                            <div class="mb-3">
                                <?= $this->Form->control('button_caption', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Button Caption'],
                                    'placeholder' => 'Button text (optional - uses title if empty)'
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
                <?= $this->Form->create(null, [
                    'id' => 'edit-component-form',
                    'url' => ['action' => 'editComponent', '__COMPONENT_ID__'],
                    'type' => 'file'
                ]) ?>
                    <div class="mb-3">
                        <?= $this->Form->control('title', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'id' => 'edit-title'
                        ]) ?>
                    </div>
                    
                    <!-- HTML Content -->
                    <div class="edit-field mb-3" id="edit-html-fields" style="display:none;">
                        <?= $this->Form->control('content', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'HTML Content'],
                            'rows' => 4,
                            'id' => 'edit-content'
                        ]) ?>
                    </div>
                    
                    <!-- Image Fields -->
                    <div class="edit-field" id="edit-image-fields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Choose Image Source</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="image_source" id="edit_image_url" value="url" checked>
                                <label class="form-check-label" for="edit_image_url">
                                    Image URL
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="image_source" id="edit_image_upload" value="upload">
                                <label class="form-check-label" for="edit_image_upload">
                                    Upload New Image
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="edit-current-image">
                            <label class="form-label">Current Image</label>
                            <div id="current-image-preview"></div>
                        </div>
                        
                        <div class="mb-3" id="edit-url-input">
                            <?= $this->Form->control('url', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Image URL'],
                                'id' => 'edit-url'
                            ]) ?>
                        </div>
                        
                        <div class="mb-3" id="edit-file-input" style="display:none;">
                            <?= $this->Form->control('image_file', [
                                'type' => 'file',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Upload New Image'],
                                'accept' => 'image/*'
                            ]) ?>
                            <div class="form-text">Maximum file size: 5MB. Supported formats: JPEG, PNG, GIF, WebP</div>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('alt_text', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'id' => 'edit-alt-text'
                            ]) ?>
                        </div>
                    </div>
                    
                    <!-- Link Fields -->
                    <div class="edit-field" id="edit-link-fields" style="display:none;">
                        <div class="mb-3">
                            <?= $this->Form->control('url', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Link URL'],
                                'id' => 'edit-link-url'
                            ]) ?>
                        </div>
                        <div class="mb-3">
                            <?= $this->Form->control('button_caption', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Button Caption'],
                                'placeholder' => 'Button text (optional - uses title if empty)',
                                'id' => 'edit-button-caption'
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <?= $this->Form->control('css_class', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'id' => 'edit-css-class'
                        ]) ?>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <?= $this->Form->button('Update Component', [
                            'class' => 'btn btn-primary'
                        ]) ?>
                    </div>
                <?= $this->Form->end() ?>
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
            const componentItem = this.closest('.component-item');
            
            // Get component data from the DOM
            const componentType = componentItem.querySelector('.badge').textContent.toLowerCase();
            const componentTitle = componentItem.querySelector('strong') ? 
                componentItem.querySelector('strong').textContent : '';
            
            // Update form action
            const form = document.getElementById('edit-component-form');
            form.action = form.action.replace('__COMPONENT_ID__', componentId);
            
            // Populate common fields
            document.getElementById('edit-title').value = componentTitle;
            
            // Hide all edit fields first
            document.querySelectorAll('.edit-field').forEach(field => {
                field.style.display = 'none';
            });
            
            // Show and populate fields based on component type
            if (componentType === 'html') {
                document.getElementById('edit-html-fields').style.display = 'block';
                // Get content from the component display
                const contentDiv = componentItem.querySelector('.component-content');
                if (contentDiv) {
                    // Convert br tags back to newlines
                    const content = contentDiv.innerHTML.replace(/<br\s*\/?>/gi, '\n').replace(/<[^>]*>/g, '');
                    document.getElementById('edit-content').value = content;
                }
            } else if (componentType === 'image') {
                document.getElementById('edit-image-fields').style.display = 'block';
                // Get image data
                const img = componentItem.querySelector('img');
                const urlText = componentItem.querySelector('.component-content').textContent;
                if (img) {
                    document.getElementById('edit-url').value = img.src;
                    document.getElementById('edit-alt-text').value = img.alt;
                    document.getElementById('current-image-preview').innerHTML = 
                        `<img src="${img.src}" alt="${img.alt}" style="max-width: 150px; max-height: 100px;" class="rounded">`;
                }
            } else if (componentType === 'link') {
                document.getElementById('edit-link-fields').style.display = 'block';
                // Get URL from component content
                const contentDiv = componentItem.querySelector('.component-content');
                if (contentDiv) {
                    const urlMatch = contentDiv.textContent.match(/URL:\s*(.+)/);
                    if (urlMatch) {
                        document.getElementById('edit-link-url').value = urlMatch[1].trim();
                    }
                }
                // Try to get button caption if it exists (we'll need to fetch this from server)
                fetchComponentData(componentId);
            }
            
            // Get CSS class (this would need to be stored somewhere accessible or fetched from server)
            // For now, we'll fetch the full component data
            if (componentType !== 'link') {
                fetchComponentData(componentId);
            }
        });
    });
    
    // Function to fetch full component data from server
    function fetchComponentData(componentId) {
        fetch(`<?= $this->Url->build(['action' => 'getComponent']) ?>/${componentId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.component) {
                const comp = data.component;
                document.getElementById('edit-css-class').value = comp.css_class || '';
                
                if (comp.type === 'link' && comp.button_caption) {
                    document.getElementById('edit-button-caption').value = comp.button_caption;
                }
            }
        })
        .catch(error => console.error('Error fetching component data:', error));
    }
    
    // Handle edit modal image source switching
    document.querySelectorAll('input[name="image_source"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const editUrlInput = document.getElementById('edit-url-input');
            const editFileInput = document.getElementById('edit-file-input');
            
            if (this.value === 'url') {
                editUrlInput.style.display = 'block';
                editFileInput.style.display = 'none';
                // Clear file input
                const fileField = editFileInput.querySelector('input[type="file"]');
                if (fileField) fileField.value = '';
            } else {
                editUrlInput.style.display = 'none';
                editFileInput.style.display = 'block';
                // Don't clear URL input in case user wants to switch back
            }
        });
    });
});
</script>
