<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
$this->assign('title', 'Adaugă setare');

// Definește tipurile de setări disponibile
$settingTypes = [
    'text' => 'Câmp text',
    'textarea' => 'Zonă text',
    'boolean' => 'Boolean (Bifă)',
    'number' => 'Număr',
    'email' => 'Email',
    'url' => 'URL',
    'select' => 'Opțiuni selectabile',
    'color' => 'Selector culoare'
];
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-plus-circle"></i>
                Adaugă setare nouă
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la setări',
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
                                'label' => ['text' => 'Cheie setare', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'placeholder' => 'ex: titlu_site, email_contact, etc.',
                                'required' => true,
                                'help' => 'Identificator unic pentru această setare (fără spații, folosește underscore)'
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('type', [
                                'type' => 'select',
                                'options' => $settingTypes,
                                'label' => ['text' => 'Tip setare', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'empty' => '-- Selectează tipul setării --',
                                'help' => 'Alege tipul de input pentru această setare'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('description', [
                        'type' => 'text',
                        'label' => ['text' => 'Descriere', 'class' => 'form-label'],
                        'class' => 'form-control',
                        'placeholder' => 'Descriere scurtă a ceea ce controlează această setare',
                        'help' => 'Descriere opțională pentru a identifica scopul acestei setări'
                    ]) ?>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('value', [
                        'type' => 'textarea',
                        'label' => ['text' => 'Valoare setare', 'class' => 'form-label'],
                        'class' => 'form-control',
                        'rows' => 4,
                        'placeholder' => 'Introdu valoarea pentru această setare',
                        'help' => 'Valoarea curentă pentru această setare'
                    ]) ?>
                </div>

                <div class="mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Ghid setări:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Cheie:</strong> Folosește doar litere mici, numere și underscore (ex: titlu_site, dimensiune_max_upload)</li>
                            <li><strong>Tip:</strong> Alege tipul de input potrivit pentru valoarea setării</li>
                            <li><strong>Boolean:</strong> Pentru valori adevărat/fals (va fi afișat ca bifă)</li>
                            <li><strong>Text vs Zonă text:</strong> Folosește text pentru valori scurte, zonă text pentru conținut mai lung</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        'Salvează setare',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escapeTitle' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Anulează',
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
// Validare formular și tip input dinamic pentru valoare
(function() {
    'use strict';

    window.addEventListener('load', function() {
        // Validare formular
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

        // Tip input dinamic bazat pe tipul setării
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
                        newInput.placeholder = 'Introdu valoarea pentru această setare';
                        break;

                    case 'number':
                        newInput = document.createElement('input');
                        newInput.type = 'number';
                        newInput.className = 'form-control';
                        newInput.name = 'value';
                        newInput.placeholder = 'Introdu valoarea numerică';
                        break;

                    case 'boolean':
                        newInput = document.createElement('select');
                        newInput.className = 'form-control';
                        newInput.name = 'value';
                        newInput.innerHTML = '<option value="">-- Selectează valoarea --</option><option value="1">Adevărat</option><option value="0">Fals</option>';
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
                        newInput.placeholder = 'Introdu valoarea pentru această setare';
                }

                newInput.value = currentValue;
                valueInput.parentNode.replaceChild(newInput, valueInput);
                valueInput = newInput;
            });
        }
    });
})();
</script>
