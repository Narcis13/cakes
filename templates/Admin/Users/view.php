<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Vizualizare utilizator');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user"></i>
                Vizualizare utilizator
            </h1>
            <div>
                <?= $this->Html->link(
                    '<i class="fas fa-edit"></i> Editează',
                    ['action' => 'edit', $user->id],
                    ['class' => 'btn btn-primary', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-arrow-left"></i> Înapoi la utilizatori',
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
                            <td><?= h($user->id) ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= h($user->email) ?></td>
                        </tr>
                        <tr>
                            <th>Rol</th>
                            <td>
                                <?php
                                    $roleClass = $user->role === 'admin' ? 'bg-danger' : 'bg-info';
                                    $roleLabel = $user->role === 'admin' ? 'Administrator' : 'Membru personal';
                                ?>
                                <span class="badge <?= $roleClass ?>"><?= $roleLabel ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Creat</th>
                            <td><?= $user->created ? $user->created->format('d F Y, H:i') : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Ultima modificare</th>
                            <td><?= $user->modified ? $user->modified->format('d F Y, H:i') : 'N/A' ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h3>Permisiuni rol</h3>
                    <div class="alert alert-info">
                        <?php if ($user->role === 'admin'): ?>
                            <i class="fas fa-shield-alt"></i>
                            <strong>Acces administrator:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Acces complet la toate funcționalitățile panoului de administrare</li>
                                <li>Poate gestiona utilizatorii și atribui roluri</li>
                                <li>Poate modifica tot conținutul și setările</li>
                                <li>Poate vizualiza rapoartele și analizele sistemului</li>
                            </ul>
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                            <strong>Acces membru personal:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Poate gestiona conținutul (pagini, noutăți, etc.)</li>
                                <li>Poate încărca și gestiona fișiere</li>
                                <li>Nu poate gestiona utilizatori sau setările sistemului</li>
                                <li>Acces limitat la rapoarte</li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Editează utilizator',
                        ['action' => 'edit', $user->id],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Șterge utilizator',
                        ['action' => 'delete', $user->id],
                        [
                            'class' => 'btn btn-danger',
                            'confirm' => 'Ești sigur că vrei să ștergi acest utilizator?',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
