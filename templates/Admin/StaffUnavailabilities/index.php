<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\StaffUnavailability> $staffUnavailabilities
 */
$this->assign('title', 'Indisponibilități Personal');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-times"></i>
                Indisponibilități Personal
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> Adaugă Indisponibilitate',
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
                                <th><?= $this->Paginator->sort('staff_id', 'Membru Personal') ?></th>
                                <th><?= $this->Paginator->sort('date_from', 'De la data') ?></th>
                                <th><?= $this->Paginator->sort('date_to', 'Până la data') ?></th>
                                <th>Durată</th>
                                <th><?= $this->Paginator->sort('reason', 'Motiv') ?></th>
                                <th><?= $this->Paginator->sort('created', 'Creat') ?></th>
                                <th class="actions text-center">Acțiuni</th>
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
                                <td><?= h($staffUnavailability->date_from->format('d.m.Y')) ?></td>
                                <td><?= h($staffUnavailability->date_to->format('d.m.Y')) ?></td>
                                <td>
                                    <?php
                                        $days = $staffUnavailability->date_from->diffInDays($staffUnavailability->date_to) + 1;
                                        echo $days . ' ' . ($days === 1 ? 'zi' : 'zile');
                                    ?>
                                </td>
                                <td><?= h($staffUnavailability->reason) ?></td>
                                <td><?= h($staffUnavailability->created->format('d.m.Y')) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $staffUnavailability->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-info', 'title' => 'Vizualizare']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $staffUnavailability->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-primary', 'title' => 'Editare']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $staffUnavailability->id],
                                        [
                                            'confirm' => __('Sigur doriți să ștergeți această indisponibilitate?'),
                                            'escape' => false,
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Ștergere'
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
                    <p class="text-muted">Nu s-au găsit indisponibilități pentru personal.</p>
                </div>
                <?php endif; ?>

                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->first('<< ' . __('Prima')) ?>
                        <?= $this->Paginator->prev('< ' . __('Anterioară')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('Următoarea') . ' >') ?>
                        <?= $this->Paginator->last(__('Ultima') . ' >>') ?>
                    </ul>
                    <p><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrări din totalul de {{count}}')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
