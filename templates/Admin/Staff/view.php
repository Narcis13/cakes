<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Staff $staffMember
 */
?>
<?php $this->assign('title', $staffMember->name); ?>

<div class="staff view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><?= h($staffMember->name) ?></h3>
            <?php if ($staffMember->is_active): ?>
                <span class="badge bg-success">Activ</span>
            <?php else: ?>
                <span class="badge bg-secondary">Inactiv</span>
            <?php endif; ?>
            <?php
            $typeLabels = [
                'doctor' => 'Medic',
                'nurse' => 'Asistent',
                'technician' => 'Tehnician',
                'administrator' => 'Administrator',
                'other' => 'Altul'
            ];
            ?>
            <span class="badge bg-primary ms-1"><?= h($typeLabels[$staffMember->staff_type] ?? ucfirst($staffMember->staff_type)) ?></span>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Editează',
                ['action' => 'edit', $staffMember->id],
                ['class' => 'btn btn-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-list"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i> Informații personale</h5>
                </div>
                <div class="card-body">
                    <?php if ($staffMember->photo): ?>
                        <div class="text-center mb-3">
                            <img src="<?= $this->Url->build('/img/staff/' . $staffMember->photo) ?>"
                                 class="img-fluid rounded-circle"
                                 alt="<?= h($staffMember->name) ?>"
                                 style="max-width: 200px; object-fit: cover;">
                        </div>
                    <?php else: ?>
                        <div class="text-center mb-3">
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center text-white"
                                 style="width: 200px; height: 200px; font-size: 64px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    <?php endif; ?>

                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%;">Nume:</th>
                            <td><?= h($staffMember->name) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Titlu:</th>
                            <td><?= h($staffMember->title ?: '-') ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Specializare:</th>
                            <td><?= h($staffMember->specialization_data->name ?? $staffMember->specialization ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tip personal:</th>
                            <td><span class="badge bg-primary"><?= h($typeLabels[$staffMember->staff_type] ?? ucfirst($staffMember->staff_type)) ?></span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Experiență:</th>
                            <td>
                                <?php if ($staffMember->years_experience): ?>
                                    <?= h($staffMember->years_experience) ?> ani
                                <?php else: ?>
                                    <span class="text-muted">Nespecificat</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-address-card"></i> Informații de contact</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <?php if ($staffMember->phone): ?>
                        <tr>
                            <th class="text-muted" style="width: 30%;">Telefon:</th>
                            <td>
                                <a href="tel:<?= h($staffMember->phone) ?>" class="text-decoration-none">
                                    <i class="fas fa-phone"></i> <?= h($staffMember->phone) ?>
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($staffMember->email): ?>
                        <tr>
                            <th class="text-muted">Email:</th>
                            <td>
                                <a href="mailto:<?= h($staffMember->email) ?>" class="text-decoration-none">
                                    <i class="fas fa-envelope"></i> <?= h($staffMember->email) ?>
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($staffMember->department): ?>
                        <tr>
                            <th class="text-muted">Departament:</th>
                            <td>
                                <?= $this->Html->link(
                                    '<i class="fas fa-building"></i> ' . h($staffMember->department->name),
                                    ['controller' => 'Departments', 'action' => 'view', $staffMember->department->id],
                                    ['class' => 'text-decoration-none', 'escape' => false]
                                ) ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    <?php if (!$staffMember->phone && !$staffMember->email && !$staffMember->department): ?>
                        <p class="text-muted text-center mb-0">Nu sunt disponibile informații de contact</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?php if ($staffMember->bio): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Biografie</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($staffMember->bio)) ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($staffMember->appointments)): ?>
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar"></i> Programări viitoare</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Ora</th>
                                    <th>Pacient</th>
                                    <th>Serviciu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($staffMember->appointments as $appointment): ?>
                                <tr>
                                    <td><?= h($appointment->appointment_date->format('j M Y')) ?></td>
                                    <td><?= h($appointment->appointment_time ? $appointment->appointment_time->format('H:i') : '-') ?></td>
                                    <td>
                                        <?php if ($appointment->patient_name): ?>
                                            <?= h($appointment->patient_name) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Nespecificat</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($appointment->service): ?>
                                            <?= h($appointment->service->name) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClasses = [
                                            'scheduled' => 'bg-primary',
                                            'confirmed' => 'bg-success',
                                            'completed' => 'bg-secondary',
                                            'cancelled' => 'bg-danger',
                                            'no_show' => 'bg-warning'
                                        ];
                                        $statusLabels = [
                                            'scheduled' => 'Programat',
                                            'confirmed' => 'Confirmat',
                                            'completed' => 'Finalizat',
                                            'cancelled' => 'Anulat',
                                            'no_show' => 'Neprezentare'
                                        ];
                                        $statusClass = $statusClasses[$appointment->status] ?? 'bg-secondary';
                                        $statusLabel = $statusLabels[$appointment->status] ?? $appointment->status;
                                        ?>
                                        <span class="badge <?= $statusClass ?> text-uppercase" style="font-size: 0.7rem;">
                                            <?= h($statusLabel) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted mt-2 mb-0">
                        <small>Afișând următoarele 10 programări viitoare</small>
                    </p>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p>Nu există programări viitoare</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-clock"></i> Informații înregistrare</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 30%;">Creat:</th>
                            <td><?= h($staffMember->created->format('j M Y H:i')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modificat:</th>
                            <td><?= h($staffMember->modified->format('j M Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
