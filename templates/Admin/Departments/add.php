<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 * @var array $staff
 */
?>
<?php $this->assign('title', 'Add Department'); ?>

<div class="department add content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Add Department') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
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
                            ],
                            'checked' => true
                        ]) ?>
                    </fieldset>
                </div>
                
                <div class="col-md-4">
                    <fieldset>
                        <legend><?= __('Department Picture') ?></legend>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('picture_file', [
                                'type' => 'file',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Upload Picture'],
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
                <?= $this->Form->button(__('Save Department'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Cancel'), ['action' => 'index'], [
                    'class' => 'btn btn-secondary ms-2'
                ]) ?>
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
