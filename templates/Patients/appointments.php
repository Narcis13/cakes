<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 * @var \Cake\Collection\CollectionInterface $upcomingAppointments
 * @var \Cake\Collection\CollectionInterface $pastAppointments
 */
$this->assign('title', 'Programările mele');

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

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">
                    <i class="fas fa-calendar-check"></i>
                    Programările mele
                </h1>
            </div>
            <div>
                <?= $this->Html->link(
                    '<i class="fas fa-calendar-plus"></i> Programare nouă',
                    ['controller' => 'Appointments', 'action' => 'index'],
                    ['class' => 'btn btn-portal', 'escape' => false]
                ) ?>
            </div>
        </div>
    </div>
</div>

<!-- Programări viitoare -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-clock text-success"></i>
            Programări viitoare
        </h5>
    </div>
    <div class="card-body">
        <?php if ($upcomingAppointments->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Ora</th>
                            <th>Medic</th>
                            <th>Serviciu</th>
                            <th>Status</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcomingAppointments as $appointment): ?>
                        <tr>
                            <td>
                                <strong><?= $appointment->appointment_date->format('d.m.Y') ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?php
                                    $days = ['Duminică', 'Luni', 'Marți', 'Miercuri', 'Joi', 'Vineri', 'Sâmbătă'];
                                    echo $days[(int)$appointment->appointment_date->format('w')];
                                    ?>
                                </small>
                            </td>
                            <td>
                                <strong><?= $appointment->appointment_time->format('H:i') ?></strong>
                            </td>
                            <td>
                                <?php if ($appointment->doctor): ?>
                                    Dr. <?= h($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name) ?>
                                    <?php if ($appointment->doctor->specialization ?? null): ?>
                                        <br>
                                        <small class="text-muted"><?= h($appointment->doctor->specialization) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $appointment->service ? h($appointment->service->name) : '-' ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $statusClass[$appointment->status] ?? 'secondary' ?> badge-status">
                                    <?= $statusText[$appointment->status] ?? $appointment->status ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $appointment->id ?>">
                                    <i class="fas fa-times"></i> Anulează
                                </button>

                                <!-- Cancel Modal -->
                                <div class="modal fade" id="cancelModal<?= $appointment->id ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    Anulare programare
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Sigur doriți să anulați această programare?</p>
                                                <div class="alert alert-light">
                                                    <strong>Data:</strong> <?= $appointment->appointment_date->format('d.m.Y') ?><br>
                                                    <strong>Ora:</strong> <?= $appointment->appointment_time->format('H:i') ?><br>
                                                    <?php if ($appointment->doctor): ?>
                                                        <strong>Medic:</strong> Dr. <?= h($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name) ?><br>
                                                    <?php endif; ?>
                                                    <?php if ($appointment->service): ?>
                                                        <strong>Serviciu:</strong> <?= h($appointment->service->name) ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-arrow-left"></i> Înapoi
                                                </button>
                                                <?= $this->Form->postLink(
                                                    '<i class="fas fa-times"></i> Anulează programarea',
                                                    '/portal/appointments/cancel/' . $appointment->id,
                                                    [
                                                        'class' => 'btn btn-danger',
                                                        'escape' => false
                                                    ]
                                                ) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-3">Nu aveți programări viitoare.</p>
                <?= $this->Html->link(
                    '<i class="fas fa-calendar-plus"></i> Faceți o programare',
                    ['controller' => 'Appointments', 'action' => 'index'],
                    ['class' => 'btn btn-portal', 'escape' => false]
                ) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Istoric programări -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history text-muted"></i>
            Istoric programări
        </h5>
    </div>
    <div class="card-body">
        <?php if ($pastAppointments->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Ora</th>
                            <th>Medic</th>
                            <th>Serviciu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pastAppointments as $appointment): ?>
                        <tr class="text-muted">
                            <td>
                                <?= $appointment->appointment_date->format('d.m.Y') ?>
                            </td>
                            <td>
                                <?= $appointment->appointment_time->format('H:i') ?>
                            </td>
                            <td>
                                <?php if ($appointment->doctor): ?>
                                    Dr. <?= h($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $appointment->service ? h($appointment->service->name) : '-' ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $statusClass[$appointment->status] ?? 'secondary' ?> badge-status">
                                    <?= $statusText[$appointment->status] ?? $appointment->status ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <p class="text-muted mb-0">Nu aveți programări anterioare.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
