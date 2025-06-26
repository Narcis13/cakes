<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\File $file
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus"></i> Upload File</h2>
    <?= $this->Html->link(
        '<i class="fas fa-arrow-left"></i> Back to Files',
        ['action' => 'index'],
        ['class' => 'btn btn-secondary', 'escape' => false]
    ) ?>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->Form->create($file, [
            'type' => 'file',
            'class' => 'needs-validation',
            'novalidate' => true
        ]) ?>
        
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4">
                    <label class="form-label">Select File *</label>
                    <?= $this->Form->control('file', [
                        'type' => 'file',
                        'class' => 'form-control',
                        'required' => true,
                        'label' => false,
                        'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar'
                    ]) ?>
                    <div class="form-text">
                        Allowed file types: PDF, Word documents, Excel spreadsheets, PowerPoint presentations, 
                        text files, images, and archives. Maximum size: 10MB.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('description', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Description'],
                        'placeholder' => 'Brief description of the file...',
                        'rows' => 3
                    ]) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('category', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Category'],
                        'placeholder' => 'e.g., Documents, Reports, Brochures'
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Visibility</label>
                    <div class="form-check form-switch">
                        <?= $this->Form->control('is_public', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => ['class' => 'form-check-label', 'text' => 'Make file publicly accessible'],
                            'checked' => true
                        ]) ?>
                    </div>
                    <div class="form-text">
                        Public files can be accessed directly via URL. Private files require authentication.
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <?= $this->Html->link(
                'Cancel',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary me-md-2']
            ) ?>
            <?= $this->Form->button(
                '<i class="fas fa-upload"></i> Upload File',
                ['type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File preview and validation
    const fileInput = document.querySelector('input[type="file"]');
    const form = document.querySelector('form');
    
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size
            if (file.size > 10 * 1024 * 1024) {
                alert('File size exceeds 10MB limit.');
                this.value = '';
                return;
            }
            
            // Auto-populate category based on file type
            const categoryInput = document.querySelector('input[name="category"]');
            if (!categoryInput.value) {
                const fileName = file.name.toLowerCase();
                if (fileName.includes('.pdf')) {
                    categoryInput.value = 'Documents';
                } else if (fileName.includes('.jpg') || fileName.includes('.png') || fileName.includes('.gif')) {
                    categoryInput.value = 'Images';
                } else if (fileName.includes('.doc') || fileName.includes('.docx')) {
                    categoryInput.value = 'Documents';
                } else if (fileName.includes('.xls') || fileName.includes('.xlsx')) {
                    categoryInput.value = 'Spreadsheets';
                } else if (fileName.includes('.ppt') || fileName.includes('.pptx')) {
                    categoryInput.value = 'Presentations';
                }
            }
        }
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a file to upload.');
            return false;
        }
    });
});
</script>
