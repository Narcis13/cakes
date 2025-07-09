<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScheduleException $scheduleException
 * @var array $staff
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Form->postLink(
                __('Ștergere'),
                ['action' => 'delete', $scheduleException->id],
                ['confirm' => __('Sigur doriți să ștergeți această excepție?'), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('Lista Excepții'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="scheduleExceptions form content">
            <?= $this->Form->create($scheduleException) ?>
            <fieldset>
                <legend><?= __('Editare Excepție Program') ?></legend>
                
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('staff_id', [
                                    'label' => 'Medic',
                                    'options' => $staff,
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('exception_date', [
                                    'label' => 'Data',
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
                                        '1' => 'Zi lucrată suplimentar (medicul va lucra în această zi)',
                                        '0' => 'Zi liberă (medicul NU va lucra în această zi)'
                                    ],
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row" id="time-fields" style="<?= $scheduleException->is_working ? '' : 'display: none;' ?>">
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
                        
                        <?= $this->Form->control('reason', [
                            'label' => 'Motiv',
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'rows' => 3,
                            'placeholder' => 'Ex: Concediu medical, Congres medical, Program special, etc.'
                        ]) ?>
                    </div>
                </div>
            </fieldset>
            
            <div class="mt-3">
                <?= $this->Form->button(__('Salvează'), ['class' => 'btn btn-primary']) ?>
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
});
</script>