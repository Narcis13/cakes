<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 * @var array $staff
 * @var array $services
 * @var array $statuses
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Form->postLink(
                __('Ștergere'),
                ['action' => 'delete', $appointment->id],
                ['confirm' => __('Sigur doriți să ștergeți programarea #{0}?', $appointment->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('Lista Programări'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="appointments form content">
            <?= $this->Form->create($appointment) ?>
            <fieldset>
                <legend><?= __('Editare Programare') ?></legend>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informații Programare</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('appointment_date', [
                                    'label' => 'Data',
                                    'type' => 'date',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('appointment_time', [
                                    'label' => 'Ora',
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('doctor_id', [
                                    'label' => 'Medic',
                                    'options' => $staff,
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('service_id', [
                                    'label' => 'Serviciu',
                                    'options' => $services,
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('status', [
                                    'label' => 'Status',
                                    'options' => $statuses,
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('end_time', [
                                    'label' => 'Ora finalizare (calculată automat)',
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'readonly' => true,
                                    'help' => 'Se calculează automat în funcție de durata serviciului'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informații Pacient</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('patient_name', [
                                    'label' => 'Nume și Prenume',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('patient_cnp', [
                                    'label' => 'CNP',
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('patient_email', [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('patient_phone', [
                                    'label' => 'Telefon',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <?= $this->Form->control('notes', [
                            'label' => 'Observații',
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'rows' => 3
                        ]) ?>
                    </div>
                </div>
                
                <?php if ($appointment->status === 'cancelled'): ?>
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5>Informații Anulare</h5>
                    </div>
                    <div class="card-body">
                        <?= $this->Form->control('cancellation_reason', [
                            'label' => 'Motiv anulare',
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'rows' => 3
                        ]) ?>
                    </div>
                </div>
                <?php endif; ?>
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
    // Auto-calculate end time when service or time changes
    const serviceSelect = document.getElementById('service-id');
    const timeInput = document.getElementById('appointment-time');
    const endTimeInput = document.getElementById('end-time');
    
    function updateEndTime() {
        // This would need AJAX to get service duration
        // For now, just showing the concept
    }
    
    if (serviceSelect) {
        serviceSelect.addEventListener('change', updateEndTime);
    }
    
    if (timeInput) {
        timeInput.addEventListener('change', updateEndTime);
    }
});
</script>