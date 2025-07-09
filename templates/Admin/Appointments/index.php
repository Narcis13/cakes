<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Appointment> $appointments
 * @var array $doctors
 * @var array $statuses
 */
?>
<div class="appointments index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Programări') ?></h3>
        <div>
            <?= $this->Html->link(__('Programări de Azi'), ['action' => 'today'], ['class' => 'btn btn-info']) ?>
            <?= $this->Html->link(__('Export CSV'), ['action' => 'export', '?' => $this->request->getQuery()], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Filtre</h5>
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3']) ?>
            
            <div class="col-md-3">
                <?= $this->Form->control('date_from', [
                    'label' => 'De la data',
                    'type' => 'date',
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('date_from')
                ]) ?>
            </div>
            
            <div class="col-md-3">
                <?= $this->Form->control('date_to', [
                    'label' => 'Până la data',
                    'type' => 'date',
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('date_to')
                ]) ?>
            </div>
            
            <div class="col-md-3">
                <?= $this->Form->control('doctor_id', [
                    'label' => 'Medic',
                    'options' => ['' => 'Toți medicii'] + $doctors,
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('doctor_id')
                ]) ?>
            </div>
            
            <div class="col-md-3">
                <?= $this->Form->control('status', [
                    'label' => 'Status',
                    'options' => ['' => 'Toate'] + $statuses,
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('status')
                ]) ?>
            </div>
            
            <div class="col-md-6">
                <?= $this->Form->control('patient_name', [
                    'label' => 'Nume pacient',
                    'class' => 'form-control',
                    'placeholder' => 'Căutare după nume...',
                    'value' => $this->request->getQuery('patient_name')
                ]) ?>
            </div>
            
            <div class="col-md-6 d-flex align-items-end">
                <?= $this->Form->button(__('Filtrează'), ['class' => 'btn btn-primary me-2']) ?>
                <?= $this->Html->link(__('Resetează'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('appointment_date', 'Data') ?></th>
                    <th><?= $this->Paginator->sort('appointment_time', 'Ora') ?></th>
                    <th><?= $this->Paginator->sort('patient_name', 'Pacient') ?></th>
                    <th>Contact</th>
                    <th><?= $this->Paginator->sort('doctor_id', 'Medic') ?></th>
                    <th><?= $this->Paginator->sort('service_id', 'Serviciu') ?></th>
                    <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                    <th class="actions"><?= __('Acțiuni') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= $appointment->appointment_date->format('d.m.Y') ?></td>
                    <td><?= $appointment->appointment_time->format('H:i') ?></td>
                    <td><?= h($appointment->patient_name) ?></td>
                    <td>
                        <small>
                            <?= $this->Html->link($appointment->patient_phone, 'tel:' . $appointment->patient_phone) ?><br>
                            <?= $this->Html->link($appointment->patient_email, 'mailto:' . $appointment->patient_email) ?>
                        </small>
                    </td>
                    <td><?= $appointment->has('staff') ? h($appointment->staff->name) : '' ?></td>
                    <td><?= $appointment->has('service') ? h($appointment->service->name) : '' ?></td>
                    <td>
                        <?php
                        $statusClass = [
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'cancelled' => 'danger',
                            'completed' => 'info',
                            'no-show' => 'secondary'
                        ];
                        $statusText = [
                            'pending' => 'În așteptare',
                            'confirmed' => 'Confirmată',
                            'cancelled' => 'Anulată',
                            'completed' => 'Finalizată',
                            'no-show' => 'Neprezentare'
                        ];
                        ?>
                        <span class="badge bg-<?= $statusClass[$appointment->status] ?? 'secondary' ?>">
                            <?= $statusText[$appointment->status] ?? $appointment->status ?>
                        </span>
                    </td>
                    <td class="actions">
                        <?= $this->Html->link(__('Vizualizare'), ['action' => 'view', $appointment->id], ['class' => 'btn btn-sm btn-primary']) ?>
                        <?= $this->Html->link(__('Editare'), ['action' => 'edit', $appointment->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        
                        <?php if ($appointment->status !== 'cancelled' && $appointment->status !== 'completed'): ?>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $appointment->id ?>">
                                Anulează
                            </button>
                            
                            <!-- Cancel Modal -->
                            <div class="modal fade" id="cancelModal<?= $appointment->id ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Anulare Programare</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <?= $this->Form->create(null, ['url' => ['action' => 'cancel', $appointment->id]]) ?>
                                        <div class="modal-body">
                                            <p>Sigur doriți să anulați programarea pentru <strong><?= h($appointment->patient_name) ?></strong>?</p>
                                            <?= $this->Form->control('cancellation_reason', [
                                                'label' => 'Motiv anulare',
                                                'type' => 'textarea',
                                                'class' => 'form-control',
                                                'rows' => 3
                                            ]) ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                                            <?= $this->Form->button(__('Anulează Programarea'), ['class' => 'btn btn-danger']) ?>
                                        </div>
                                        <?= $this->Form->end() ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('Prima')) ?>
            <?= $this->Paginator->prev('< ' . __('Anterioară')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Următoarea') . ' >') ?>
            <?= $this->Paginator->last(__('Ultima') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrări din totalul de {{count}}')) ?></p>
    </div>
</div>