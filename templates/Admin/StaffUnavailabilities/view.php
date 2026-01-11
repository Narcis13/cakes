<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StaffUnavailability $staffUnavailability
 */
$this->assign('title', 'Vizualizare Indisponibilitate Personal');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-calendar-times"></i>
                Vizualizare Indisponibilitate Personal
            </h1>
            <div>
                <?= $this->Html->link(
                    '<i class="fas fa-edit"></i> Editare',
                    ['action' => 'edit', $staffUnavailability->id],
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
                            <td><?= h($staffUnavailability->id) ?></td>
                        </tr>
                        <tr>
                            <th>Membru Personal</th>
                            <td>
                                <?= $staffUnavailability->hasValue('staff') ?
                                    $this->Html->link(
                                        h($staffUnavailability->staff->name),
                                        ['controller' => 'Staff', 'action' => 'view', $staffUnavailability->staff->id]
                                    ) : '' ?>
                            </td>
                        </tr>
                        <tr>
                            <th>De la data</th>
                            <td><?= h($staffUnavailability->date_from->format('d.m.Y')) ?></td>
                        </tr>
                        <tr>
                            <th>Până la data</th>
                            <td><?= h($staffUnavailability->date_to->format('d.m.Y')) ?></td>
                        </tr>
                        <tr>
                            <th>Durată</th>
                            <td>
                                <?php
                                    $days = $staffUnavailability->date_from->diffInDays($staffUnavailability->date_to) + 1;
                                    echo $days . ' ' . ($days === 1 ? 'zi' : 'zile');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Motiv</th>
                            <td><?= h($staffUnavailability->reason) ?: '<em class="text-muted">Nespecificat</em>' ?></td>
                        </tr>
                        <tr>
                            <th>Creat</th>
                            <td><?= $staffUnavailability->created ? $staffUnavailability->created->format('d.m.Y, H:i') : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Ultima Modificare</th>
                            <td><?= $staffUnavailability->modified ? $staffUnavailability->modified->format('d.m.Y, H:i') : 'N/A' ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h3>Detalii Perioadă Indisponibilitate</h3>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Impact:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Membrul personalului nu va apărea în opțiunile de programare în această perioadă</li>
                            <li>Orice programări existente în această perioadă ar trebui reprogramate</li>
                            <li>Această perioadă include atât data de început cât și data de sfârșit</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Editare Indisponibilitate',
                        ['action' => 'edit', $staffUnavailability->id],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Ștergere Indisponibilitate',
                        ['action' => 'delete', $staffUnavailability->id],
                        [
                            'class' => 'btn btn-danger',
                            'confirm' => 'Sigur doriți să ștergeți această perioadă de indisponibilitate?',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
