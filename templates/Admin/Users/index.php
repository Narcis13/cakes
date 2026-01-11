<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
$this->assign('title', 'Utilizatori');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-users"></i>
                Gestionare utilizatori
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> Adaugă utilizator nou',
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if ($users->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                                <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                                <th><?= $this->Paginator->sort('role', 'Rol') ?></th>
                                <th><?= $this->Paginator->sort('created', 'Creat') ?></th>
                                <th>Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $this->Number->format($user->id) ?></td>
                                <td><?= h($user->email) ?></td>
                                <td>
                                    <span class="badge bg-<?= $user->role === 'admin' ? 'danger' : 'primary' ?>">
                                        <?= h(ucfirst($user->role)) ?>
                                    </span>
                                </td>
                                <td><?= h($user->created->format('d.m.Y H:i')) ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $user->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Vizualizează']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $user->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Editează']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $user->id],
                                        [
                                            'confirm' => __('Ești sigur că vrei să ștergi utilizatorul {0}?', $user->email),
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Șterge'
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
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nu s-au găsit utilizatori</h5>
                    <p class="text-muted">Începe prin adăugarea primului utilizator.</p>
                    <?= $this->Html->link(
                        'Adaugă utilizator nou',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
