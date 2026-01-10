<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Specialization $specialization
 */
?>
<?php $this->assign('title', $specialization->name); ?>

<div class="specializations view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><?= h($specialization->name) ?></h3>
            <?php if ($specialization->is_active): ?>
                <span class="badge bg-success">Activ</span>
            <?php else: ?>
                <span class="badge bg-secondary">Inactiv</span>
            <?php endif; ?>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Editează',
                ['action' => 'edit', $specialization->id],
                ['class' => 'btn btn-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-list"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Detalii specializare</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 30%;">Nume:</th>
                            <td><?= h($specialization->name) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status:</th>
                            <td>
                                <?php if ($specialization->is_active): ?>
                                    <span class="badge bg-success">Activ</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactiv</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Creat:</th>
                            <td><?= h($specialization->created->format('j M Y H:i')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modificat:</th>
                            <td><?= h($specialization->modified->format('j M Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if ($specialization->description): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-file-text"></i> Descriere</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($specialization->description)) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user-md"></i> Personal asociat</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($specialization->staff)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nume</th>
                                        <th>Departament</th>
                                        <th>Status</th>
                                        <th>Acțiune</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($specialization->staff as $staffMember): ?>
                                    <tr>
                                        <td>
                                            <?= $this->Html->link(
                                                h($staffMember->first_name . ' ' . $staffMember->last_name),
                                                ['controller' => 'Staff', 'action' => 'view', $staffMember->id],
                                                ['class' => 'text-decoration-none']
                                            ) ?>
                                        </td>
                                        <td>
                                            <?php if ($staffMember->department): ?>
                                                <small><?= h($staffMember->department->name) ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($staffMember->is_active): ?>
                                                <span class="badge bg-success">Activ</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactiv</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $this->Html->link(
                                                '<i class="fas fa-eye"></i>',
                                                ['controller' => 'Staff', 'action' => 'view', $staffMember->id],
                                                ['class' => 'btn btn-sm btn-outline-info', 'escape' => false, 'title' => 'Vizualizează']
                                            ) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> Niciun membru al personalului nu este asociat cu această specializare încă.
                        </p>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus"></i> Adaugă personal',
                            ['controller' => 'Staff', 'action' => 'add'],
                            ['class' => 'btn btn-sm btn-primary d-block mx-auto', 'style' => 'width: fit-content;', 'escape' => false]
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i> Statistici</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted">Total personal:</th>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= isset($specialization->staff) ? count($specialization->staff) : 0 ?> medici
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Personal activ:</th>
                            <td>
                                <span class="badge bg-success">
                                    <?php
                                    $activeCount = 0;
                                    if (isset($specialization->staff)) {
                                        foreach ($specialization->staff as $staff) {
                                            if ($staff->is_active) $activeCount++;
                                        }
                                    }
                                    echo $activeCount;
                                    ?> medici
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
