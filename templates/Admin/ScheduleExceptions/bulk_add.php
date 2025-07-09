<?php
/**
 * @var \App\View\AppView $this
 * @var array $staff
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Html->link(__('Lista Excepții'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Adaugă Individual'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="scheduleExceptions form content">
            <?= $this->Form->create(null) ?>
            <fieldset>
                <legend><?= __('Adaugă Excepții în Masă') ?></legend>
                <p class="text-info">Această funcție permite adăugarea de excepții pentru mai multe zile consecutive.</p>
                
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->Form->control('staff_id', [
                                    'label' => 'Medic',
                                    'options' => $staff,
                                    'class' => 'form-control',
                                    'required' => true,
                                    'empty' => '-- Selectați medicul --'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('date_from', [
                                    'label' => 'De la data',
                                    'type' => 'date',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('date_to', [
                                    'label' => 'Până la data',
                                    'type' => 'date',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->Form->control('is_working', [
                                    'label' => 'Tip excepție',
                                    'type' => 'radio',
                                    'options' => [
                                        '1' => 'Zile lucrate suplimentar (medicul va lucra în aceste zile)',
                                        '0' => 'Zile libere (medicul NU va lucra în aceste zile)'
                                    ],
                                    'required' => true,
                                    'default' => '0'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row" id="time-fields" style="display: none;">
                            <div class="col-md-6">
                                <?= $this->Form->control('start_time', [
                                    'label' => 'Ora început',
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'help' => 'Lăsați gol pentru a folosi orarul standard'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('end_time', [
                                    'label' => 'Ora sfârșit',
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'help' => 'Lăsați gol pentru a folosi orarul standard'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->Form->control('skip_weekends', [
                                    'label' => 'Omite weekend-urile (Sâmbătă și Duminică)',
                                    'type' => 'checkbox',
                                    'class' => 'form-check-input',
                                    'default' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <?= $this->Form->control('reason', [
                            'label' => 'Motiv',
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'rows' => 3,
                            'placeholder' => 'Ex: Concediu, Congres medical, Program special de sărbători, etc.'
                        ]) ?>
                        
                        <div class="alert alert-warning mt-3">
                            <strong>Atenție:</strong> Zilele care au deja excepții definite vor fi omise automat.
                        </div>
                    </div>
                </div>
            </fieldset>
            
            <div class="mt-3">
                <?= $this->Form->button(__('Creează Excepții'), ['class' => 'btn btn-primary']) ?>
                <?= $this->Html->link(__('Anulează'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isWorkingRadios = document.querySelectorAll('input[name="is_working"]');
    const timeFields = document.getElementById('time-fields');
    
    function toggleTimeFields() {
        const selectedValue = document.querySelector('input[name="is_working"]:checked')?.value;
        if (selectedValue === '1') {
            timeFields.style.display = 'block';
        } else {
            timeFields.style.display = 'none';
        }
    }
    
    isWorkingRadios.forEach(radio => {
        radio.addEventListener('change', toggleTimeFields);
    });
    
    // Initial check
    toggleTimeFields();
    
    // Date validation
    const dateFrom = document.getElementById('date-from');
    const dateTo = document.getElementById('date-to');
    
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function() {
            dateTo.min = this.value;
        });
        
        dateTo.addEventListener('change', function() {
            dateFrom.max = this.value;
        });
    }
});
</script>