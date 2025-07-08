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
                <span class="badge bg-success">Active</span>
            <?php else: ?>
                <span class="badge bg-secondary">Inactive</span>
            <?php endif; ?>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Edit',
                ['action' => 'edit', $service->id],
                ['class' => 'btn btn-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-list"></i> Back to List',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Service Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 35%;">Service Name:</th>
                            <td><?= h($service->name) ?></td>
                        </tr>
                        <?php if ($service->department): ?>
                        <tr>
                            <th class="text-muted">Department:</th>
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
                            <th class="text-muted">Duration:</th>
                            <td>
                                <i class="fas fa-clock"></i> <?= h($service->duration_minutes) ?> minutes
                                <small class="text-muted">(<?= h(intval($service->duration_minutes / 60)) ?> hours <?= h($service->duration_minutes % 60) ?> mins)</small>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($service->price !== null): ?>
                        <tr>
                            <th class="text-muted">Price:</th>
                            <td>
                                <strong class="text-success">$<?= $this->Number->format($service->price, ['places' => 2]) ?></strong>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th class="text-muted">Status:</th>
                            <td>
                                <?php if ($service->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Created:</th>
                            <td><?= h($service->created->format('M j, Y g:i A')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modified:</th>
                            <td><?= h($service->modified->format('M j, Y g:i A')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php if ($service->requirements): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-clipboard-list"></i> Requirements</h5>
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
                    <h5><i class="fas fa-file-text"></i> Description</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($service->description)) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($service->appointments)): ?>
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar"></i> Upcoming Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($service->appointments as $appointment): ?>
                                <tr>
                                    <td><?= h($appointment->appointment_date->format('M j, Y')) ?></td>
                                    <td><?= h($appointment->appointment_time ? $appointment->appointment_time->format('g:i A') : '-') ?></td>
                                    <td>
                                        <?php if ($appointment->patient_name): ?>
                                            <?= h($appointment->patient_name) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not specified</span>
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
                                        $statusClass = $statusClasses[$appointment->status] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $statusClass ?> text-uppercase" style="font-size: 0.7rem;">
                                            <?= h($appointment->status) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted mt-2 mb-0">
                        <small>Showing next 10 upcoming appointments</small>
                    </p>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p>No upcoming appointments for this service</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>