<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DoctorSchedule $doctorSchedule
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Doctor Schedule'), ['action' => 'edit', $doctorSchedule->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Doctor Schedule'), ['action' => 'delete', $doctorSchedule->id], ['confirm' => __('Are you sure you want to delete # {0}?', $doctorSchedule->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Doctor Schedules'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Doctor Schedule'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="doctorSchedules view content">
            <h3><?= h($doctorSchedule->id) ?></h3>
            <table class="table">
                <tr>
                    <th><?= __('Doctor') ?></th>
                    <td><?= $doctorSchedule->has('staff') ? $this->Html->link($doctorSchedule->staff->name, ['controller' => 'Staff', 'action' => 'view', $doctorSchedule->staff->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Service') ?></th>
                    <td><?= $doctorSchedule->has('service') ? $this->Html->link($doctorSchedule->service->name, ['controller' => 'Services', 'action' => 'view', $doctorSchedule->service->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Day of Week') ?></th>
                    <td><?= h($doctorSchedule->day_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Time Range') ?></th>
                    <td><?= h($doctorSchedule->time_range) ?></td>
                </tr>
                <tr>
                    <th><?= __('Start Time') ?></th>
                    <td><?= h($doctorSchedule->start_time->format('H:i')) ?></td>
                </tr>
                <tr>
                    <th><?= __('End Time') ?></th>
                    <td><?= h($doctorSchedule->end_time->format('H:i')) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maximum Appointments') ?></th>
                    <td><?= $this->Number->format($doctorSchedule->max_appointments) ?></td>
                </tr>
                <tr>
                    <th><?= __('Slot Duration') ?></th>
                    <td>
                        <?php if ($doctorSchedule->slot_duration): ?>
                            <?= $this->Number->format($doctorSchedule->slot_duration) ?> <?= __('minutes') ?>
                        <?php else: ?>
                            <em><?= __('Using service default') ?></em>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Buffer Minutes') ?></th>
                    <td><?= $this->Number->format($doctorSchedule->buffer_minutes) ?> <?= __('minutes') ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Slots') ?></th>
                    <td><?= $this->Number->format($doctorSchedule->total_slots) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td>
                        <span class="badge bg-<?= $doctorSchedule->is_active ? 'success' : 'danger' ?>">
                            <?= $doctorSchedule->is_active ? __('Active') : __('Inactive') ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($doctorSchedule->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($doctorSchedule->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>