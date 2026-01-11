<?php
/**
 * @var \App\View\AppView $this
 * @var array $schedulesByDay
 * @var array $daysOfWeek
 */
?>
<div class="doctorSchedules calendar content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Calendar Program Medici') ?></h3>
        <div>
            <?= $this->Html->link(__('Vizualizare Listă'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Html->link(__('Adaugă Program'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="calendar-container">
        <div class="row">
            <?php foreach ($daysOfWeek as $dayNumber => $dayName): ?>
                <div class="col-md-12 col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><?= h($dayName) ?></h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($schedulesByDay[$dayNumber])): ?>
                                <?php foreach ($schedulesByDay[$dayNumber] as $schedule): ?>
                                    <div class="schedule-item mb-3 p-3 border rounded <?= !$schedule->is_active ? 'opacity-50' : '' ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <?= $this->Html->link(
                                                        h($schedule->staff->name),
                                                        ['controller' => 'Staff', 'action' => 'view', $schedule->staff->id],
                                                        ['class' => 'text-decoration-none']
                                                    ) ?>
                                                </h6>
                                                <p class="mb-1">
                                                    <i class="far fa-clock"></i>
                                                    <strong><?= h($schedule->time_range) ?></strong>
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fas fa-stethoscope"></i>
                                                    <?= h($schedule->service->name) ?>
                                                </p>
                                                <p class="mb-0 small text-muted">
                                                    <?= __('Max: {0} programări', $schedule->max_appointments) ?>
                                                    <?php if ($schedule->slot_duration): ?>
                                                        | <?= __n('{0} min intervale', '{0} min intervale', $schedule->slot_duration, $schedule->slot_duration) ?>
                                                    <?php endif; ?>
                                                    <?php if ($schedule->buffer_minutes > 0): ?>
                                                        | <?= __('{0} min tampon', $schedule->buffer_minutes) ?>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <?php if (!$schedule->is_active): ?>
                                                    <span class="badge bg-danger mb-2"><?= __('Inactiv') ?></span>
                                                    <br>
                                                <?php endif; ?>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <?= $this->Html->link(
                                                        '<i class="fas fa-edit"></i>',
                                                        ['action' => 'edit', $schedule->id],
                                                        ['class' => 'btn btn-outline-secondary', 'escape' => false, 'title' => __('Editare')]
                                                    ) ?>
                                                    <?= $this->Form->postLink(
                                                        '<i class="fas fa-trash"></i>',
                                                        ['action' => 'delete', $schedule->id],
                                                        [
                                                            'confirm' => __('Sigur doriți să ștergeți acest program?'),
                                                            'class' => 'btn btn-outline-danger',
                                                            'escape' => false,
                                                            'title' => __('Ștergere')
                                                        ]
                                                    ) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center my-4">
                                    <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                    <?= __('Niciun program pentru această zi') ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.schedule-item:hover {
    background-color: #f8f9fa;
}
.opacity-50 {
    opacity: 0.5;
}
</style>
