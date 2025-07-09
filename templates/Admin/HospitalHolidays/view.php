<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\HospitalHoliday $hospitalHoliday
 */
$this->assign('title', 'View Hospital Holiday');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-alt"></i>
                View Hospital Holiday
            </h1>
            <div>
                <?= $this->Html->link(
                    '<i class="fas fa-edit"></i> Edit',
                    ['action' => 'edit', $hospitalHoliday->id],
                    ['class' => 'btn btn-primary', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-arrow-left"></i> Back to List',
                    ['action' => 'index'],
                    ['class' => 'btn btn-secondary', 'escape' => false]
                ) ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="200">ID</th>
                            <td><?= h($hospitalHoliday->id) ?></td>
                        </tr>
                        <tr>
                            <th>Holiday Name</th>
                            <td><strong><?= h($hospitalHoliday->name) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>
                                <?= h($hospitalHoliday->date->format('F j, Y')) ?>
                                <span class="text-muted">(<?= h($hospitalHoliday->date->format('l')) ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>
                                <?php if ($hospitalHoliday->is_recurring): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-redo"></i> Recurring Annually
                                    </span>
                                    <br>
                                    <small class="text-muted">This holiday will occur on <?= $hospitalHoliday->date->format('F j') ?> every year</small>
                                <?php else: ?>
                                    <span class="badge bg-secondary">One-time Holiday</span>
                                    <br>
                                    <small class="text-muted">This holiday only applies to <?= $hospitalHoliday->date->format('Y') ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?= $hospitalHoliday->description ? nl2br(h($hospitalHoliday->description)) : '<em class="text-muted">No description provided</em>' ?></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $hospitalHoliday->created ? $hospitalHoliday->created->format('F j, Y, g:i a') : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Last Modified</th>
                            <td><?= $hospitalHoliday->modified ? $hospitalHoliday->modified->format('F j, Y, g:i a') : 'N/A' ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h3>Holiday Impact</h3>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>When this holiday is active:</strong>
                        <ul class="mb-0 mt-2">
                            <li>The hospital will be marked as closed on <?= $hospitalHoliday->date->format('F j') ?><?= $hospitalHoliday->is_recurring ? ' every year' : ', ' . $hospitalHoliday->date->format('Y') ?></li>
                            <li>Patients will not be able to book appointments on this date</li>
                            <li>Any existing appointments should be rescheduled</li>
                            <li>The date will be shown as unavailable in the appointment booking calendar</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Edit Holiday',
                        ['action' => 'edit', $hospitalHoliday->id],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Delete Holiday',
                        ['action' => 'delete', $hospitalHoliday->id],
                        [
                            'class' => 'btn btn-danger',
                            'confirm' => 'Are you sure you want to delete this holiday?',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>