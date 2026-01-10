<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\HospitalHoliday $hospitalHoliday
 */
$this->assign('title', 'Vizualizare Sărbătoare Spital');

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
                Vizualizare Sărbătoare Spital
            </h1>
            <div>
                <?= $this->Html->link(
                    '<i class="fas fa-edit"></i> Editare',
                    ['action' => 'edit', $hospitalHoliday->id],
                    ['class' => 'btn btn-primary', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-arrow-left"></i> Înapoi la Listă',
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
                            <th>Nume Sărbătoare</th>
                            <td><strong><?= h($hospitalHoliday->name) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Data</th>
                            <td>
                                <?= h($hospitalHoliday->date->format('d.m.Y')) ?>
                                <span class="text-muted">(<?= $daysOfWeek[$hospitalHoliday->date->format('l')] ?? $hospitalHoliday->date->format('l') ?>)</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tip</th>
                            <td>
                                <?php if ($hospitalHoliday->is_recurring): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-redo"></i> Recurent Anual
                                    </span>
                                    <br>
                                    <small class="text-muted">Această sărbătoare va avea loc pe <?= $hospitalHoliday->date->format('d F') ?> în fiecare an</small>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Sărbătoare o singură dată</span>
                                    <br>
                                    <small class="text-muted">Această sărbătoare se aplică doar pentru <?= $hospitalHoliday->date->format('Y') ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Descriere</th>
                            <td><?= $hospitalHoliday->description ? nl2br(h($hospitalHoliday->description)) : '<em class="text-muted">Nicio descriere furnizată</em>' ?></td>
                        </tr>
                        <tr>
                            <th>Creat</th>
                            <td><?= $hospitalHoliday->created ? $hospitalHoliday->created->format('d.m.Y, H:i') : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Ultima Modificare</th>
                            <td><?= $hospitalHoliday->modified ? $hospitalHoliday->modified->format('d.m.Y, H:i') : 'N/A' ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h3>Impactul Sărbătorii</h3>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Când această sărbătoare este activă:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Spitalul va fi marcat ca închis pe <?= $hospitalHoliday->date->format('d F') ?><?= $hospitalHoliday->is_recurring ? ' în fiecare an' : ', ' . $hospitalHoliday->date->format('Y') ?></li>
                            <li>Pacienții nu vor putea face programări în această dată</li>
                            <li>Orice programări existente ar trebui reprogramate</li>
                            <li>Data va fi afișată ca indisponibilă în calendarul de programări</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Editare Sărbătoare',
                        ['action' => 'edit', $hospitalHoliday->id],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Ștergere Sărbătoare',
                        ['action' => 'delete', $hospitalHoliday->id],
                        [
                            'class' => 'btn btn-danger',
                            'confirm' => 'Sigur doriți să ștergeți această sărbătoare?',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
