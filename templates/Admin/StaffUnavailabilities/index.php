<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\StaffUnavailability> $staffUnavailabilities
 */
$this->assign('title', 'Staff Unavailabilities');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-times"></i>
                Staff Unavailabilities
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> Add Unavailability',
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('staff_id', 'Staff Member') ?></th>
                                <th><?= $this->Paginator->sort('date_from', 'From Date') ?></th>
                                <th><?= $this->Paginator->sort('date_to', 'To Date') ?></th>
                                <th>Duration</th>
                                <th><?= $this->Paginator->sort('reason') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th class="actions text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staffUnavailabilities as $staffUnavailability): ?>
                            <tr>
                                <td>
                                    <?= $staffUnavailability->hasValue('staff') ? 
                                        $this->Html->link(
                                            h($staffUnavailability->staff->name),
                                            ['controller' => 'Staff', 'action' => 'view', $staffUnavailability->staff->id]
                                        ) : '' ?>
                                </td>
                                <td><?= h($staffUnavailability->date_from->format('F j, Y')) ?></td>
                                <td><?= h($staffUnavailability->date_to->format('F j, Y')) ?></td>
                                <td>
                                    <?php
                                        $days = $staffUnavailability->date_from->diffInDays($staffUnavailability->date_to) + 1;
                                        echo $days . ' ' . ($days === 1 ? 'day' : 'days');
                                    ?>
                                </td>
                                <td><?= h($staffUnavailability->reason) ?></td>
                                <td><?= h($staffUnavailability->created->format('M j, Y')) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $staffUnavailability->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-info', 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $staffUnavailability->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-primary', 'title' => 'Edit']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $staffUnavailability->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete this unavailability?'),
                                            'escape' => false,
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Delete'
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (count($staffUnavailabilities) === 0): ?>
                <div class="text-center py-4">
                    <p class="text-muted">No staff unavailabilities found.</p>
                </div>
                <?php endif; ?>
                
                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->first('<< ' . __('first')) ?>
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                        <?= $this->Paginator->last(__('last') . ' >>') ?>
                    </ul>
                    <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>