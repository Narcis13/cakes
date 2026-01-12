<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Html->link(__('Editare Programare'), ['action' => 'edit', $appointment->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Ștergere Programare'), ['action' => 'delete', $appointment->id], ['confirm' => __('Sigur doriți să ștergeți programarea #{0}?', $appointment->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Lista Programări'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="appointments view content">
            <h3>Programare #<?= h($appointment->id) ?></h3>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informații Programare</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th><?= __('Status') ?></th>
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
                                <span class="badge bg-<?= $statusClass[$appointment->status] ?? 'secondary' ?> fs-6">
                                    <?= $statusText[$appointment->status] ?? $appointment->status ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th><?= __('Data') ?></th>
                            <td><?= $appointment->appointment_date->i18nFormat('EEEE, d MMMM yyyy', null, 'ro_RO') ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Ora') ?></th>
                            <td><?= $appointment->appointment_time->format('H:i') ?> - <?= $appointment->end_time->format('H:i') ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Medic') ?></th>
                            <td><?= $appointment->doctor ? $this->Html->link($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name, ['controller' => 'Staff', 'action' => 'view', $appointment->doctor->id]) : '-' ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Serviciu') ?></th>
                            <td><?= $appointment->service ? $this->Html->link($appointment->service->name, ['controller' => 'Services', 'action' => 'view', $appointment->service->id]) : '-' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informații Pacient</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th><?= __('Nume') ?></th>
                            <td><?= h($appointment->patient_name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Email') ?></th>
                            <td><?= $this->Html->link($appointment->patient_email, 'mailto:' . $appointment->patient_email) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Telefon') ?></th>
                            <td><?= $this->Html->link($appointment->patient_phone, 'tel:' . $appointment->patient_phone) ?></td>
                        </tr>
                        <?php if ($appointment->patient_cnp): ?>
                        <tr>
                            <th><?= __('CNP') ?></th>
                            <td><?= h($appointment->patient_cnp) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <?php if ($appointment->notes): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Observații</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($appointment->notes)); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($appointment->status === 'cancelled' && $appointment->cancellation_reason): ?>
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5>Informații Anulare</h5>
                </div>
                <div class="card-body">
                    <p><strong>Anulată la:</strong> <?= $appointment->cancelled_at ? $appointment->cancelled_at->format('d.m.Y H:i') : '-' ?></p>
                    <p><strong>Motiv:</strong> <?= h($appointment->cancellation_reason) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5>Informații Sistem</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th><?= __('Token Confirmare') ?></th>
                            <td><?= $appointment->confirmation_token ? substr($appointment->confirmation_token, 0, 20) . '...' : '-' ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Confirmată la') ?></th>
                            <td><?= $appointment->confirmed_at ? $appointment->confirmed_at->format('d.m.Y H:i') : '-' ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Creată') ?></th>
                            <td><?= $appointment->created->format('d.m.Y H:i') ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Modificată') ?></th>
                            <td><?= $appointment->modified->format('d.m.Y H:i') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <?php if ($appointment->status === 'pending'): ?>
                    <?= $this->Html->link(__('Confirmă Programarea'), ['action' => 'edit', $appointment->id], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                
                <?php if (!in_array($appointment->status, ['cancelled', 'completed'])): ?>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Anulează Programarea
                    </button>
                <?php endif; ?>
                
                <?= $this->Html->link(__('Editare'), ['action' => 'edit', $appointment->id], ['class' => 'btn btn-warning']) ?>
                <?= $this->Html->link(__('Înapoi la Listă'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    </div>
</div>

<?php if (!in_array($appointment->status, ['cancelled', 'completed'])): ?>
<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
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
                    'rows' => 3,
                    'required' => true
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