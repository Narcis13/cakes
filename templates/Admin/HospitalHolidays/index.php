<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\HospitalHoliday> $hospitalHolidays
 * @var string $year
 * @var array $yearsList
 */
$this->assign('title', 'Sărbători Spital');

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
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-alt"></i>
                Sărbători Spital
            </h1>
            <div class="d-flex gap-2">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex gap-2']) ?>
                <?= $this->Form->control('year', [
                    'type' => 'select',
                    'options' => $yearsList,
                    'value' => $year,
                    'label' => false,
                    'class' => 'form-control',
                    'onchange' => 'this.form.submit()'
                ]) ?>
                <?= $this->Form->end() ?>
                <?= $this->Html->link(
                    '<i class="fas fa-plus"></i> Adaugă Sărbătoare',
                    ['action' => 'add'],
                    ['class' => 'btn btn-primary', 'escape' => false]
                ) ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name', 'Nume') ?></th>
                                <th><?= $this->Paginator->sort('date', 'Data') ?></th>
                                <th>Zi din săptămână</th>
                                <th><?= $this->Paginator->sort('is_recurring', 'Recurent') ?></th>
                                <th>Descriere</th>
                                <th><?= $this->Paginator->sort('created', 'Creat') ?></th>
                                <th class="actions text-center">Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hospitalHolidays as $hospitalHoliday): ?>
                            <tr>
                                <td>
                                    <strong><?= h($hospitalHoliday->name) ?></strong>
                                </td>
                                <td>
                                    <?= h($hospitalHoliday->date->format('d.m.Y')) ?>
                                </td>
                                <td>
                                    <?= $daysOfWeek[$hospitalHoliday->date->format('l')] ?? $hospitalHoliday->date->format('l') ?>
                                </td>
                                <td>
                                    <?php if ($hospitalHoliday->is_recurring): ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-redo"></i> Recurent Anual
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">O singură dată</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= h($hospitalHoliday->description) ?: '<em class="text-muted">Fără descriere</em>' ?>
                                </td>
                                <td><?= h($hospitalHoliday->created->format('d.m.Y')) ?></td>
                                <td class="actions text-center">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $hospitalHoliday->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-info', 'title' => 'Vizualizare']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $hospitalHoliday->id],
                                        ['escape' => false, 'class' => 'btn btn-sm btn-primary', 'title' => 'Editare']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $hospitalHoliday->id],
                                        [
                                            'confirm' => __('Sigur doriți să ștergeți sărbătoarea "{0}"?', $hospitalHoliday->name),
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

                <?php if (count($hospitalHolidays) === 0): ?>
                <div class="text-center py-4">
                    <p class="text-muted">Nu s-au găsit sărbători pentru <?= h($year) ?>.</p>
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

        <div class="card mt-3">
            <div class="card-body">
                <h5><i class="fas fa-info-circle"></i> Tipuri de Sărbători</h5>
                <ul>
                    <li><strong>Sărbătoare o singură dată:</strong> Se aplică doar la data specifică din anul dat</li>
                    <li><strong>Sărbătoare recurentă:</strong> Se aplică automat la aceeași dată (lună și zi) în fiecare an</li>
                </ul>
            </div>
        </div>
    </div>
</div>
