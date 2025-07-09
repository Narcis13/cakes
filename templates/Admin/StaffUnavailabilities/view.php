<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StaffUnavailability $staffUnavailability
 */
$this->assign('title', 'View Staff Unavailability');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-times"></i>
                View Staff Unavailability
            </h1>
            <div>
                <?= $this->Html->link(
                    '<i class="fas fa-edit"></i> Edit',
                    ['action' => 'edit', $staffUnavailability->id],
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
                            <td><?= h($staffUnavailability->id) ?></td>
                        </tr>
                        <tr>
                            <th>Staff Member</th>
                            <td>
                                <?= $staffUnavailability->hasValue('staff') ? 
                                    $this->Html->link(
                                        h($staffUnavailability->staff->name),
                                        ['controller' => 'Staff', 'action' => 'view', $staffUnavailability->staff->id]
                                    ) : '' ?>
                            </td>
                        </tr>
                        <tr>
                            <th>From Date</th>
                            <td><?= h($staffUnavailability->date_from->format('F j, Y')) ?></td>
                        </tr>
                        <tr>
                            <th>To Date</th>
                            <td><?= h($staffUnavailability->date_to->format('F j, Y')) ?></td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>
                                <?php
                                    $days = $staffUnavailability->date_from->diffInDays($staffUnavailability->date_to) + 1;
                                    echo $days . ' ' . ($days === 1 ? 'day' : 'days');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Reason</th>
                            <td><?= h($staffUnavailability->reason) ?: '<em class="text-muted">Not specified</em>' ?></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $staffUnavailability->created ? $staffUnavailability->created->format('F j, Y, g:i a') : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Last Modified</th>
                            <td><?= $staffUnavailability->modified ? $staffUnavailability->modified->format('F j, Y, g:i a') : 'N/A' ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h3>Unavailability Period Details</h3>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Impact:</strong>
                        <ul class="mb-0 mt-2">
                            <li>The staff member will not appear in appointment booking options during this period</li>
                            <li>Any existing appointments during this period should be rescheduled</li>
                            <li>This period includes both start and end dates</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Edit Unavailability',
                        ['action' => 'edit', $staffUnavailability->id],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Delete Unavailability',
                        ['action' => 'delete', $staffUnavailability->id],
                        [
                            'class' => 'btn btn-danger',
                            'confirm' => 'Are you sure you want to delete this unavailability period?',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>