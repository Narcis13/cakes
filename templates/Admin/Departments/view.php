<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<?php $this->assign('title', $department->name); ?>

<div class="department view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><?= h($department->name) ?></h3>
            <?php if ($department->is_active): ?>
                <span class="badge bg-success">Activ</span>
            <?php else: ?>
                <span class="badge bg-secondary">Inactiv</span>
            <?php endif; ?>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-edit"></i> Editează',
                ['action' => 'edit', $department->id],
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informații departament</h5>
                </div>
                <div class="card-body">
                    <?php if ($department->picture): ?>
                        <div class="text-center mb-3">
                            <img src="<?= $this->Url->build('/img/departments/' . $department->picture) ?>"
                                 class="img-fluid rounded"
                                 alt="<?= h($department->name) ?>"
                                 style="max-height: 200px; object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%;">Nume:</th>
                            <td><?= h($department->name) ?></td>
                        </tr>
                        <?php if ($department->head_doctor): ?>
                        <tr>
                            <th class="text-muted">Medic șef:</th>
                            <td>
                                <i class="fas fa-user-md"></i> <?= h($department->head_doctor->first_name . ' ' . $department->head_doctor->last_name) ?>
                                <?php if ($department->head_doctor->title): ?>
                                    <br><small class="text-muted"><?= h($department->head_doctor->title) ?></small>
                                <?php elseif ($department->head_doctor->specialization): ?>
                                    <br><small class="text-muted"><?= h($department->head_doctor->specialization) ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($department->phone): ?>
                        <tr>
                            <th class="text-muted">Telefon:</th>
                            <td>
                                <a href="tel:<?= h($department->phone) ?>" class="text-decoration-none">
                                    <i class="fas fa-phone"></i> <?= h($department->phone) ?>
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($department->email): ?>
                        <tr>
                            <th class="text-muted">Email:</th>
                            <td>
                                <a href="mailto:<?= h($department->email) ?>" class="text-decoration-none">
                                    <i class="fas fa-envelope"></i> <?= h($department->email) ?>
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($department->floor_location): ?>
                        <tr>
                            <th class="text-muted">Locație:</th>
                            <td><i class="fas fa-map-marker-alt"></i> <?= h($department->floor_location) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th class="text-muted">Creat:</th>
                            <td><?= h($department->created->format('j M Y H:i')) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Modificat:</th>
                            <td><?= h($department->modified->format('j M Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?php if ($department->description): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-file-text"></i> Descriere</h5>
                </div>
                <div class="card-body">
                    <?= $this->Text->autoParagraph(h($department->description)) ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($department->services_html): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-stethoscope"></i> Servicii</h5>
                </div>
                <div class="card-body">
                    <?= $department->services_html ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($department->services)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-list"></i> Servicii asociate</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($department->services as $service): ?>
                        <div class="col-md-6 mb-2">
                            <div class="border rounded p-2">
                                <strong><?= h($service->name) ?></strong>
                                <?php if ($service->description): ?>
                                    <br><small class="text-muted"><?= $this->Text->truncate(h($service->description), 100) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($department->staff)): ?>
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-users"></i> Membrii personalului</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($department->staff as $staff): ?>
                        <div class="col-md-6 mb-2">
                            <div class="border rounded p-2">
                                <strong><?= h($staff->first_name . ' ' . $staff->last_name) ?></strong>
                                <?php if ($staff->title): ?>
                                    <br><small class="text-muted"><?= h($staff->title) ?></small>
                                <?php elseif ($staff->specialization): ?>
                                    <br><small class="text-muted"><?= h($staff->specialization) ?></small>
                                <?php endif; ?>
                                <?php if ($staff->phone): ?>
                                    <br><small><i class="fas fa-phone"></i> <?= h($staff->phone) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
