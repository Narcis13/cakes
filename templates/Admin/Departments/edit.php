<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 * @var array $staff
 */
?>
<?php $this->assign('title', 'Edit Department'); ?>

<div class="department edit content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Edit Department: {0}', h($department->name)) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> View',
                ['action' => 'view', $department->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($department, ['type' => 'file']) ?>
            
            <div class="row">
                <div class="col-md-8">
                    <fieldset>
                        <legend><?= __('Basic Information') ?></legend>
                        
                        <?= $this->Form->control('name', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'required' => true
                        ]) ?>
                        
                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'rows' => 4,
                            'placeholder' => 'Enter department description...'
                        ]) ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('phone', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'placeholder' => 'e.g., +1-234-567-8900'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('email', [
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'placeholder' => 'department@hospital.com'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('floor_location', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Floor/Location'],
                                    'placeholder' => 'e.g., Floor 3, Wing A'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('head_doctor_id', [
                                    'type' => 'select',
                                    'options' => $staff,
                                    'empty' => 'Select Head Doctor',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Head Doctor']
                                ]) ?>
                            </div>
                        </div>
                        
                        <?= $this->Form->control('is_active', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => [
                                'class' => 'form-check-label',
                                'text' => 'Department is active'
                            ],
                            'templates' => [
                                'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
                                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                            ]
                        ]) ?>
                    </fieldset>
                </div>
                
                <div class="col-md-4">
                    <fieldset>
                        <legend><?= __('Department Picture') ?></legend>
                        
                        <?php if ($department->picture): ?>
                        <div class="current-image mb-3">
                            <label class="form-label">Current Picture:</label>
                            <div class="text-center">
                                <img src="<?= $this->Url->build('/img/departments/' . $department->picture) ?>" 
                                     class="img-fluid rounded border" 
                                     style="max-height: 200px; object-fit: cover;"
                                     alt="<?= h($department->name) ?>">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('picture_file', [
                                'type' => 'file',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => $department->picture ? 'Change Picture' : 'Upload Picture'],
                                'accept' => 'image/*'
                            ]) ?>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Supported formats: JPG, PNG, GIF, WebP. Max size: 5MB.
                                </small>
                            </div>
                        </div>
                        
                        <div id="imagePreview" style="display: none;">
                            <label class="form-label">New Picture Preview:</label>
                            <img id="previewImg" class="img-fluid rounded border" style="max-height: 200px;">
                        </div>
                    </fieldset>
                </div>
            </div>
            
            <fieldset class="mt-4">
                <legend><?= __('Services Information') ?></legend>
                
                <?= $this->Form->control('services_html', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => ['class' => 'form-label', 'text' => 'Services Description (HTML)'],
                    'rows' => 8,
                    'placeholder' => 'Enter detailed information about services offered by this department. You can use HTML tags for formatting.'
                ]) ?>
                
                <div class="form-text">
                    <small class="text-muted">
                        <i class="fas fa-code"></i> 
                        You can use HTML tags like &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, &lt;em&gt;, etc. for better formatting.
                    </small>
                </div>
            </fieldset>
            
            <div class="form-actions mt-4">
                <?= $this->Form->button(__('Update Department'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Cancel'), ['action' => 'index'], [
                    'class' => 'btn btn-secondary ms-2'
                ]) ?>
                <?= $this->Form->postLink(
                    __('Delete Department'),
                    ['action' => 'delete', $department->id],
                    [
                        'class' => 'btn btn-danger ms-2',
                        'confirm' => __('Are you sure you want to delete "{0}"?', $department->name)
                    ]
                ) ?>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="picture_file"]');
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
