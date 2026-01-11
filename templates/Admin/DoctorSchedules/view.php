<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DoctorSchedule $doctorSchedule
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Html->link(__('Editare Program Medic'), ['action' => 'edit', $doctorSchedule->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Ștergere Program Medic'), ['action' => 'delete', $doctorSchedule->id], ['confirm' => __('Sigur doriți să ștergeți programul #{0}?', $doctorSchedule->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Lista Program Medici'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Program Nou'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="doctorSchedules view content">
            <h3><?= h($doctorSchedule->id) ?></h3>
            <table class="table">
                <tr>
                    <th><?= __('Medic') ?></th>
                    <td><?= $doctorSchedule->has('staff') ? $this->Html->link($doctorSchedule->staff->name, ['controller' => 'Staff', 'action' => 'view', $doctorSchedule->staff->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Serviciu') ?></th>
                    <td><?= $doctorSchedule->has('service') ? $this->Html->link($doctorSchedule->service->name, ['controller' => 'Services', 'action' => 'view', $doctorSchedule->service->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Zi din săptămână') ?></th>
                    <td><?= h($doctorSchedule->day_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Interval orar') ?></th>
                    <td><?= h($doctorSchedule->time_range) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ora început') ?></th>
                    <td><?= h($doctorSchedule->start_time->format('H:i')) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ora sfârșit') ?></th>
                    <td><?= h($doctorSchedule->end_time->format('H:i')) ?></td>
                </tr>
                <tr>
                    <th><?= __('Număr maxim programări') ?></th>
                    <td><?= $this->Number->format($doctorSchedule->max_appointments) ?></td>
                </tr>
                <tr>
                    <th><?= __('Durată interval') ?></th>
                    <td>
                        <?php if ($doctorSchedule->slot_duration): ?>
                            <?= $this->Number->format($doctorSchedule->slot_duration) ?> <?= __('minute') ?>
                        <?php else: ?>
                            <em><?= __('Folosește durata implicită a serviciului') ?></em>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Minute tampon') ?></th>
                    <td><?= $this->Number->format($doctorSchedule->buffer_minutes) ?> <?= __('minute') ?></td>
                </tr>
                <tr>
                    <th><?= __('Total intervale') ?></th>
                    <td><?= $this->Number->format($doctorSchedule->total_slots) ?></td>
                </tr>
                <tr>
                    <th><?= __('Stare') ?></th>
                    <td>
                        <span class="badge bg-<?= $doctorSchedule->is_active ? 'success' : 'danger' ?>">
                            <?= $doctorSchedule->is_active ? __('Activ') : __('Inactiv') ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Creat') ?></th>
                    <td><?= h($doctorSchedule->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modificat') ?></th>
                    <td><?= h($doctorSchedule->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
