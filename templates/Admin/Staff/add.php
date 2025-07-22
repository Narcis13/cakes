<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Staff $staffMember
 * @var array $departments
 */
?>
<?php $this->assign('title', 'Add Staff Member'); ?>

<div class="staff add content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Add Staff Member') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to List',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($staffMember, ['type' => 'file']) ?>
            
            <div class="row">
                <div class="col-md-8">
                    <fieldset>
                        <legend><?= __('Personal Information') ?></legend>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('first_name', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'required' => true,
                                    'placeholder' => 'John'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('last_name', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'required' => true,
                                    'placeholder' => 'Doe'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('title', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Title/Position'],
                                    'placeholder' => 'e.g., Dr., Head of Department, Senior Surgeon'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('specialization_id', [
                                    'type' => 'select',
                                    'options' => $specializations,
                                    'empty' => 'Select Specialization',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Specialization']
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?= $this->Form->control('staff_type', [
                                    'type' => 'select',
                                    'options' => [
                                        'doctor' => 'Doctor',
                                        'nurse' => 'Nurse',
                                        'technician' => 'Technician',
                                        'administrator' => 'Administrator',
                                        'other' => 'Other'
                                    ],
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Staff Type'],
                                    'default' => 'doctor'
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('department_id', [
                                    'type' => 'select',
                                    'options' => $departments,
                                    'empty' => 'Select Department',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Department']
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('years_experience', [
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Years of Experience'],
                                    'min' => 0,
                                    'max' => 60,
                                    'placeholder' => '0'
                                ]) ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mt-4">
                        <legend><?= __('Contact Information') ?></legend>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('phone', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'placeholder' => '+1-234-567-8900'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('email', [
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'placeholder' => 'doctor@hospital.com'
                                ]) ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mt-4">
                        <legend><?= __('Biography') ?></legend>
                        
                        <?= $this->Form->control('bio', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label'],
                            'rows' => 5,
                            'placeholder' => 'Brief professional biography, qualifications, and achievements...'
                        ]) ?>
                    </fieldset>

                    <div class="mt-3">
                        <?= $this->Form->control('is_active', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => [
                                'class' => 'form-check-label',
                                'text' => 'Staff member is active'
                            ],
                            'templates' => [
                                'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
                                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                            ],
                            'checked' => true
                        ]) ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <fieldset>
                        <legend><?= __('Profile Photo') ?></legend>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('photo_file', [
                                'type' => 'file',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Upload Photo'],
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
                            <label class="form-label">Photo Preview:</label>
                            <div class="text-center">
                                <img id="previewImg" class="img-fluid rounded-circle" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb"></i> <strong>Tips:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>Use a professional headshot</li>
                                <li>Square images work best</li>
                                <li>Minimum recommended size: 300x300px</li>
                            </ul>
                        </div>
                    </fieldset>
                </div>
            </div>
            
            <div class="mt-4">
                <?= $this->Form->button(__('Save Staff Member'), [
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="photo_file"]');
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