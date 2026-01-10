<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 */
?>
<?php $this->assign('title', $service->name); ?>

<div class="service view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><?= h($service->name) ?></h3>
            <?php if ($service->is_active): ?>
                <span class="badge bg-success">Activ</span>
            <?php else: ?>
                <span class="badge bg-secondary">Inactiv</span>
            <?php endif; ?>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Editează',
                ['action' => 'edit', $service->id],
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informații serviciu</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 35%;">Nume serviciu:</th>
                            <td><?= h($service->name) ?></td>
                        </tr>
                        <?php if ($service->department): ?>
                        <tr>
                            <th class="text-muted">Departament:</th>
                            <td>
                                <?= $this->Html->link(
                                    '<i class="fas fa-building"></i> ' . h($service->department->name),
                                    ['controller' => 'Departments', 'action' => 'view', $service->department->id],
                                    ['class' => 'text-decoration-none', 'escape' => false]
                                ) ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($service->duration_minutes): ?>
                        <tr>
                            <th class="text-muted">Durată:</th>
                            <td>
                                <i class="fas fa-clock"></i> <?= h($service->duration_minutes) ?> minute
                                <small class="text-muted">(<?= h(intval($service->duration_minutes / 60)) ?> ore <?= h($service->duration_minutes % 60) ?> min)</small>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($service->price !== null): ?>
                        <tr>
                            <th class="text-muted">Preț:</th>
                            <td>
                                <strong class="text-success"><?= $this->Number->format($service->price, ['places' => 2]) ?> RON</strong>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th class="text-muted">Status:</th>
                            <td>
                                <?php if ($service->is_active): ?>
                                    <span class="badge bg-success">Activ</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactiv</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Creat:</th>
                            <td><?= h($service->created->format('j M Y H:i')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modificat:</th>
                            <td><?= h($service->modified->format('j M Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if ($service->requirements): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-clipboard-list"></i> Cerințe</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($service->requirements)) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <?php if ($service->description): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h5><i class="fas fa-file-text"></i> Descriere</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($service->description)) ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($service->appointments)): ?>
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
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($service->appointments as $appointment): ?>
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
                    <p>Nu există programări viitoare pentru acest serviciu</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
