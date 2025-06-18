<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
$this->assign('title', 'Add Setting');

// Define available setting types
$settingTypes = [
    'text' => 'Text Input',
    'textarea' => 'Text Area',
    'boolean' => 'Boolean (Checkbox)',
    'number' => 'Number',
    'email' => 'Email',
    'url' => 'URL',
    'select' => 'Select Options',
    'color' => 'Color Picker'
];
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-plus-circle"></i>
                Add New Setting
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Back to Settings',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>

        <div class="card">
            <div class="card-body">
                <?= $this->Form->create($setting, ['class' => 'needs-validation', 'novalidate' => true]) ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('key_name', [
                                'type' => 'text',
                                'label' => ['text' => 'Setting Key Name', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'e.g., site_title, contact_email, etc.',
                                'required' => true,
                                'help' => 'Unique identifier for this setting (no spaces, use underscores)'
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('type', [
                                'type' => 'select',
                                'options' => $settingTypes,
                                'label' => ['text' => 'Setting Type', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'empty' => '-- Select Setting Type --',
                                'help' => 'Choose the input type for this setting'
                            ]) ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <?= $this->Form->control('description', [
                        'type' => 'text',
                        'label' => ['text' => 'Description', 'class' => 'form-label'],
                        'class' => 'form-control',
                        'placeholder' => 'Brief description of what this setting controls',
                        'help' => 'Optional description to help identify the purpose of this setting'
                    ]) ?>
                </div>
                
                <div class="mb-3">
                    <?= $this->Form->control('value', [
                        'type' => 'textarea',
                        'label' => ['text' => 'Setting Value', 'class' => 'form-label'],
                        'class' => 'form-control',
                        'rows' => 4,
                        'placeholder' => 'Enter the value for this setting',
                        'help' => 'The current value for this setting'
                    ]) ?>
                </div>
                
                <div class="mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Setting Guidelines:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Key Name:</strong> Use lowercase letters, numbers, and underscores only (e.g., site_title, max_upload_size)</li>
                            <li><strong>Type:</strong> Choose the appropriate input type for the setting value</li>
                            <li><strong>Boolean:</strong> For true/false values (will be rendered as checkbox)</li>
                            <li><strong>Text vs Textarea:</strong> Use text for short values, textarea for longer content</li>
                        </ul>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        'Save Setting',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Cancel',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary']
                    ) ?>
                </div>
                
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation and dynamic value input type
(function() {
    'use strict';
    
    window.addEventListener('load', function() {
        // Form validation
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
        
        // Dynamic value input type based on setting type
        var typeSelect = document.querySelector('select[name="type"]');
        var valueInput = document.querySelector('textarea[name="value"]');
        var valueContainer = valueInput.closest('.mb-3');
        
        if (typeSelect && valueInput) {
            typeSelect.addEventListener('change', function() {
                var selectedType = this.value;
                var currentValue = valueInput.value;
                var newInput;
                
                switch (selectedType) {
                    case 'text':
                    case 'email':
                    case 'url':
                        newInput = document.createElement('input');
                        newInput.type = selectedType === 'text' ? 'text' : selectedType;
                        newInput.className = 'form-control';
                        newInput.name = 'value';
                        newInput.placeholder = 'Enter the value for this setting';
                        break;
                        
                    case 'number':
                        newInput = document.createElement('input');
                        newInput.type = 'number';
                        newInput.className = 'form-control';
                        newInput.name = 'value';
                        newInput.placeholder = 'Enter numeric value';
                        break;
                        
                    case 'boolean':
                        newInput = document.createElement('select');
                        newInput.className = 'form-control';
                        newInput.name = 'value';
                        newInput.innerHTML = '<option value="">-- Select Value --</option><option value="1">True</option><option value="0">False</option>';
                        break;
                        
                    case 'color':
                        newInput = document.createElement('input');
                        newInput.type = 'color';
                        newInput.className = 'form-control';
                        newInput.name = 'value';
                        break;
                        
                    default:
                        newInput = document.createElement('textarea');
                        newInput.className = 'form-control';
                        newInput.name = 'value';
                        newInput.rows = 4;
                        newInput.placeholder = 'Enter the value for this setting';
                }
                
                newInput.value = currentValue;
                valueInput.parentNode.replaceChild(newInput, valueInput);
                valueInput = newInput;
            });
        }
    });
})();
</script>
