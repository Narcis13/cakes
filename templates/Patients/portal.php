<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 * @var \Cake\ORM\ResultSet $upcomingAppointments
 * @var array $appointmentStats
 */
$this->assign('title', 'Panou de control');
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-3">
            <i class="fas fa-tachometer-alt"></i>
            Panou de control
        </h1>
        <p class="lead">Bine ați revenit, <?= h($patient->full_name) ?>!</p>
    </div>
</div>

<!-- Carduri statistici -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-white" style="background-color: #1976d2;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-check fa-2x me-3"></i>
                    <div>
                        <h3 class="card-title mb-0"><?= number_format($appointmentStats['total'] ?? 0) ?></h3>
                        <p class="card-text mb-0">Total programări</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-day fa-2x me-3"></i>
                    <div>
                        <h3 class="card-title mb-0"><?= number_format($appointmentStats['upcoming'] ?? 0) ?></h3>
                        <p class="card-text mb-0">Programări viitoare</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <h3 class="card-title mb-0"><?= number_format($appointmentStats['completed'] ?? 0) ?></h3>
                        <p class="card-text mb-0">Programări finalizate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acțiuni rapide -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt"></i>
                    Acțiuni rapide
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-calendar-plus"></i> Programare nouă',
                            ['controller' => 'Appointments', 'action' => 'index'],
                            ['class' => 'btn btn-portal w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-4 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-calendar-check"></i> Programările mele',
                            ['controller' => 'Patients', 'action' => 'appointments'],
                            ['class' => 'btn btn-outline-primary w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-4 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-user-edit"></i> Editare profil',
                            ['controller' => 'Patients', 'action' => 'profile'],
                            ['class' => 'btn btn-outline-secondary w-100', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Programări viitoare -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt"></i>
                    Programări viitoare
                </h5>
                <?= $this->Html->link(
                    'Vezi toate <i class="fas fa-arrow-right"></i>',
                    ['action' => 'appointments'],
                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]
                ) ?>
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
                                            <?= $appointment->appointment_date->format('l') ?>
                                        </small>
                                    </td>
                                    <td><?= $appointment->appointment_time->format('H:i') ?></td>
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
                                        <?php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'confirmed' => 'success',
                                        ];
                                        $statusText = [
                                            'pending' => 'În așteptare',
                                            'confirmed' => 'Confirmată',
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $statusClass[$appointment->status] ?? 'secondary' ?>">
                                            <?= $statusText[$appointment->status] ?? $appointment->status ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-times"></i> Anulează',
                                            ['action' => 'cancelAppointment', $appointment->id],
                                            [
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'escape' => false,
                                                'confirm' => 'Sigur doriți să anulați această programare?'
                                            ]
                                        ) ?>
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
    </div>
</div>
