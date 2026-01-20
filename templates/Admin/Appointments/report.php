<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Appointment> $appointments
 * @var array $activeFilters
 * @var array $statuses
 */

use Cake\Core\Configure;
use Cake\I18n\DateTime;

$this->assign('title', 'Raport Programări');
$hospitalName = Configure::read('Hospital.name', 'Spitalul');
?>

<div class="print-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1>Raport Programări</h1>
            <div class="subtitle"><?= h($hospitalName) ?></div>
        </div>
        <div class="text-end">
            <div class="subtitle">Generat la: <?= DateTime::now()->format('d.m.Y H:i') ?></div>
        </div>
    </div>
</div>

<div class="filters-section">
    <?php if (!empty($activeFilters)): ?>
    <h5>Filtre aplicate:</h5>
    <ul class="filters-list">
        <?php foreach ($activeFilters as $label => $value): ?>
        <li><strong><?= h($label) ?>:</strong> <?= h($value) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p class="text-muted mb-0"><em>Toate programările (fără filtre aplicate)</em></p>
    <?php endif; ?>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-dark">
            <tr>
                <th>Data</th>
                <th>Ora</th>
                <th>Pacient</th>
                <th>Telefon</th>
                <th>Email</th>
                <th>Medic</th>
                <th>Serviciu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            foreach ($appointments as $appointment):
                $count++;
            ?>
            <tr>
                <td><?= $appointment->appointment_date->format('d.m.Y') ?></td>
                <td><?= $appointment->appointment_time->format('H:i') ?></td>
                <td><?= h($appointment->patient_name) ?></td>
                <td><?= h($appointment->patient_phone) ?></td>
                <td><?= h($appointment->patient_email) ?></td>
                <td><?= $appointment->doctor ? h($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name) : '-' ?></td>
                <td><?= $appointment->service ? h($appointment->service->name) : '-' ?></td>
                <td>
                    <?php
                    $statusClass = [
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                        'no-show' => 'secondary',
                    ];
                    $statusText = [
                        'pending' => 'În așteptare',
                        'confirmed' => 'Confirmată',
                        'cancelled' => 'Anulată',
                        'completed' => 'Finalizată',
                        'no-show' => 'Neprezentare',
                    ];
                    ?>
                    <span class="badge bg-<?= $statusClass[$appointment->status] ?? 'secondary' ?>">
                        <?= $statusText[$appointment->status] ?? $appointment->status ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if ($count === 0): ?>
            <tr>
                <td colspan="8" class="text-center text-muted">Nu există programări pentru filtrele selectate.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="print-footer d-flex justify-content-between">
    <div><strong>Total programări:</strong> <?= $count ?></div>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print"></i> Printează
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">
            Închide
        </button>
    </div>
</div>
