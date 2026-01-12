<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScheduleException $scheduleException
 */

$daysOfWeek = [
    'Sunday' => 'Duminică',
    'Monday' => 'Luni',
    'Tuesday' => 'Marți',
    'Wednesday' => 'Miercuri',
    'Thursday' => 'Joi',
    'Friday' => 'Vineri',
    'Saturday' => 'Sâmbătă'
];
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Html->link(__('Editare Excepție'), ['action' => 'edit', $scheduleException->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Ștergere Excepție'), ['action' => 'delete', $scheduleException->id], ['confirm' => __('Sigur doriți să ștergeți această excepție?'), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Lista Excepții'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Excepție Nouă'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="scheduleExceptions view content">
            <h3>Excepție Program</h3>
            
            <div class="card">
                <div class="card-header">
                    <h5>Detalii Excepție</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th><?= __('Medic') ?></th>
                            <td><?= $scheduleException->staff ? $this->Html->link($scheduleException->staff->name, ['controller' => 'Staff', 'action' => 'view', $scheduleException->staff->id]) : '-' ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Data') ?></th>
                            <td><?= $scheduleException->exception_date->format('d.m.Y') ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Zi săptămână') ?></th>
                            <td><?= $daysOfWeek[$scheduleException->exception_date->format('l')] ?? '' ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Tip excepție') ?></th>
                            <td>
                                <?php if ($scheduleException->is_working): ?>
                                    <span class="badge bg-success fs-6">Zi lucrată suplimentar</span>
                                <?php else: ?>
                                    <span class="badge bg-danger fs-6">Zi liberă</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($scheduleException->is_working): ?>
                        <tr>
                            <th><?= __('Orar') ?></th>
                            <td>
                                <?php if ($scheduleException->start_time && $scheduleException->end_time): ?>
                                    <?= $scheduleException->start_time->format('H:i') ?> - <?= $scheduleException->end_time->format('H:i') ?>
                                <?php else: ?>
                                    <span class="text-muted">Orar normal</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th><?= __('Motiv') ?></th>
                            <td><?= h($scheduleException->reason) ?: '<span class="text-muted">Nu a fost specificat</span>' ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Creată') ?></th>
                            <td><?= $scheduleException->created->format('d.m.Y H:i') ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Modificată') ?></th>
                            <td><?= $scheduleException->modified->format('d.m.Y H:i') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if ($scheduleException->is_working): ?>
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle"></i>
                <strong>Notă:</strong> În această zi, medicul va lucra suplimentar față de programul său obișnuit.
                <?php if ($scheduleException->start_time && $scheduleException->end_time): ?>
                    Programul specific pentru această zi este <?= $scheduleException->start_time->format('H:i') ?> - <?= $scheduleException->end_time->format('H:i') ?>.
                <?php else: ?>
                    Va lucra conform orarului său standard.
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Atenție:</strong> În această zi, medicul NU va fi disponibil pentru programări.
                <?php if ($scheduleException->reason): ?>
                    Motivul: <?= h($scheduleException->reason) ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <?= $this->Html->link(__('Editare'), ['action' => 'edit', $scheduleException->id], ['class' => 'btn btn-warning']) ?>
                <?= $this->Html->link(__('Înapoi la Listă'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    </div>
</div>