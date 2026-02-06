<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Patient> $patients
 */
$this->assign('title', 'Pacienți');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-injured"></i>
                Gestionare Pacienți
            </h1>
        </div>

        <!-- Filtre -->
        <div class="card mb-3">
            <div class="card-body">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
                    <div class="col-md-5">
                        <label class="form-label">Căutare</label>
                        <input type="text" name="search" class="form-control" placeholder="Nume, email sau telefon..." value="<?= h($this->request->getQuery('search')) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Toate</option>
                            <option value="active" <?= $this->request->getQuery('status') === 'active' ? 'selected' : '' ?>>Activ</option>
                            <option value="inactive" <?= $this->request->getQuery('status') === 'inactive' ? 'selected' : '' ?>>Inactiv</option>
                            <option value="verified" <?= $this->request->getQuery('status') === 'verified' ? 'selected' : '' ?>>Verificat</option>
                            <option value="unverified" <?= $this->request->getQuery('status') === 'unverified' ? 'selected' : '' ?>>Neverificat</option>
                            <option value="locked" <?= $this->request->getQuery('status') === 'locked' ? 'selected' : '' ?>>Blocat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrează
                        </button>
                        <?= $this->Html->link('Resetează', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if ($patients->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                                <th><?= $this->Paginator->sort('full_name', 'Nume') ?></th>
                                <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                                <th>Telefon</th>
                                <th>Email Verificat</th>
                                <th>Activ</th>
                                <th><?= $this->Paginator->sort('last_login_at', 'Ultimul Login') ?></th>
                                <th>Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $patient): ?>
                            <tr>
                                <td><?= $this->Number->format($patient->id) ?></td>
                                <td><?= h($patient->full_name) ?></td>
                                <td><?= h($patient->email) ?></td>
                                <td><?= h($patient->phone) ?></td>
                                <td>
                                    <?php if ($patient->is_email_verified): ?>
                                        <span class="badge bg-success">Verificat</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Neverificat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($patient->is_locked): ?>
                                        <span class="badge bg-danger">Blocat</span>
                                    <?php elseif ($patient->is_active): ?>
                                        <span class="badge bg-success">Activ</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactiv</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $patient->last_login_at ? h($patient->last_login_at->format('d.m.Y H:i')) : '<span class="text-muted">-</span>' ?>
                                </td>
                                <td>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $patient->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Vizualizează']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $patient->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Editează']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        $patient->is_active ? '<i class="fas fa-ban"></i>' : '<i class="fas fa-check"></i>',
                                        ['action' => 'toggleActive', $patient->id],
                                        [
                                            'confirm' => $patient->is_active
                                                ? __('Ești sigur că vrei să dezactivezi contul pacientului {0}?', $patient->full_name)
                                                : __('Ești sigur că vrei să activezi contul pacientului {0}?', $patient->full_name),
                                            'class' => 'btn btn-sm btn-outline-' . ($patient->is_active ? 'warning' : 'success'),
                                            'escape' => false,
                                            'title' => $patient->is_active ? 'Dezactivează' : 'Activează',
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $patient->id],
                                        [
                                            'confirm' => __('Ești sigur că vrei să ștergi pacientul {0}? Această acțiune este ireversibilă.', $patient->full_name),
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Șterge',
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginare -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <p class="text-muted">
                        <?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrare/înregistrări din {{count}} total')) ?>
                    </p>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <?= $this->Paginator->first('<< ' . __('prima')) ?>
                            <?= $this->Paginator->prev('< ' . __('anterioara')) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next(__('următoarea') . ' >') ?>
                            <?= $this->Paginator->last(__('ultima') . ' >>') ?>
                        </ul>
                    </nav>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-user-injured fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nu s-au găsit pacienți</h5>
                    <p class="text-muted">Pacienții se înregistrează prin portalul dedicat.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
