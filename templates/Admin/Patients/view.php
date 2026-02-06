<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 * @var iterable<\App\Model\Entity\Appointment> $appointments
 */
$this->assign('title', 'Pacient: ' . $patient->full_name);

$statuses = [
    'pending' => ['label' => 'În așteptare', 'class' => 'warning'],
    'confirmed' => ['label' => 'Confirmată', 'class' => 'success'],
    'cancelled' => ['label' => 'Anulată', 'class' => 'danger'],
    'completed' => ['label' => 'Finalizată', 'class' => 'info'],
    'no-show' => ['label' => 'Neprezentare', 'class' => 'secondary'],
];
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-injured"></i>
                <?= h($patient->full_name) ?>
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la pacienți',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left column: Patient info + Actions -->
    <div class="col-md-4">
        <!-- Patient Info -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-id-card"></i> Informații Pacient</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted">Nume</th>
                        <td><?= h($patient->full_name) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td><?= h($patient->email) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Telefon</th>
                        <td><?= h($patient->phone) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Înregistrat</th>
                        <td><?= h($patient->created->format('d.m.Y H:i')) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Orizont programare</th>
                        <td>
                            <?php if ($patient->orizont_extins_programare): ?>
                                <span class="badge bg-success">Extins (90 zile)</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Standard (30 zile)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Account Actions -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-cogs"></i> Acțiuni Cont</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Editează pacient',
                        ['action' => 'edit', $patient->id],
                        ['class' => 'btn btn-outline-primary', 'escape' => false]
                    ) ?>

                    <?= $this->Form->postLink(
                        $patient->is_active
                            ? '<i class="fas fa-ban"></i> Dezactivează cont'
                            : '<i class="fas fa-check"></i> Activează cont',
                        ['action' => 'toggleActive', $patient->id],
                        [
                            'confirm' => $patient->is_active
                                ? __('Ești sigur că vrei să dezactivezi contul?')
                                : __('Ești sigur că vrei să activezi contul?'),
                            'class' => 'btn btn-outline-' . ($patient->is_active ? 'warning' : 'success'),
                            'escape' => false,
                        ]
                    ) ?>

                    <?= $this->Form->postLink(
                        $patient->orizont_extins_programare
                            ? '<i class="fas fa-calendar-minus"></i> Revenire orizont standard'
                            : '<i class="fas fa-calendar-plus"></i> Activează orizont extins',
                        ['action' => 'toggleOrizontExtins', $patient->id],
                        [
                            'confirm' => $patient->orizont_extins_programare
                                ? __('Ești sigur că vrei să revii la orizontul standard de programare (30 zile)?')
                                : __('Ești sigur că vrei să activezi orizontul extins de programare (90 zile)?'),
                            'class' => 'btn btn-outline-' . ($patient->orizont_extins_programare ? 'secondary' : 'info'),
                            'escape' => false,
                        ]
                    ) ?>

                    <?php if ($patient->is_locked): ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-unlock"></i> Deblochează cont',
                            ['action' => 'unlockAccount', $patient->id],
                            [
                                'confirm' => __('Ești sigur că vrei să deblochezi contul?'),
                                'class' => 'btn btn-outline-info',
                                'escape' => false,
                            ]
                        ) ?>
                    <?php endif; ?>

                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Șterge pacient',
                        ['action' => 'delete', $patient->id],
                        [
                            'confirm' => __('Ești sigur că vrei să ștergi pacientul {0}? Această acțiune este ireversibilă.', $patient->full_name),
                            'class' => 'btn btn-outline-danger',
                            'escape' => false,
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Right column: Security + Appointments -->
    <div class="col-md-8">
        <!-- Security Info -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-shield-alt"></i> Securitate</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width: 200px;">Email verificat</th>
                        <td>
                            <?php if ($patient->is_email_verified): ?>
                                <span class="badge bg-success">Verificat</span>
                                <small class="text-muted ms-2"><?= h($patient->email_verified_at->format('d.m.Y H:i')) ?></small>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Neverificat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Cont activ</th>
                        <td>
                            <?php if ($patient->is_active): ?>
                                <span class="badge bg-success">Activ</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactiv</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Încercări eșuate</th>
                        <td>
                            <?= $patient->failed_login_attempts ?>
                            <?php if ($patient->failed_login_attempts > 0): ?>
                                <span class="badge bg-warning text-dark ms-1"><?= $patient->failed_login_attempts ?> încercări</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Blocat până la</th>
                        <td>
                            <?php if ($patient->is_locked): ?>
                                <span class="badge bg-danger">Blocat</span>
                                <small class="text-muted ms-2"><?= h($patient->locked_until->format('d.m.Y H:i')) ?></small>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Ultimul login</th>
                        <td><?= $patient->last_login_at ? h($patient->last_login_at->format('d.m.Y H:i')) : '<span class="text-muted">Niciodată</span>' ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">IP ultimul login</th>
                        <td><?= $patient->last_login_ip ? h($patient->last_login_ip) : '<span class="text-muted">-</span>' ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Appointments -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-calendar-check"></i> Programări (ultimele 20)</h5>
            </div>
            <div class="card-body">
                <?php if ($appointments->count() > 0): ?>
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
                            <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?= h($appointment->appointment_date->format('d.m.Y')) ?></td>
                                <td><?= h($appointment->appointment_time->format('H:i')) ?></td>
                                <td>
                                    <?php if ($appointment->doctor): ?>
                                        <?= h($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
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
                                    $statusInfo = $statuses[$appointment->status] ?? ['label' => $appointment->status, 'class' => 'light'];
                                    ?>
                                    <span class="badge bg-<?= $statusInfo['class'] ?>">
                                        <?= h($statusInfo['label']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['controller' => 'Appointments', 'action' => 'view', $appointment->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Vizualizează']
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-3">
                    <i class="fas fa-calendar fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Acest pacient nu are programări.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
