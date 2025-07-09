<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'View User');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user"></i>
                View User
            </h1>
            <div>
                <?= $this->Html->link(
                    '<i class="fas fa-edit"></i> Edit',
                    ['action' => 'edit', $user->id],
                    ['class' => 'btn btn-primary', 'escape' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-arrow-left"></i> Back to Users',
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
                            <th>Role</th>
                            <td>
                                <?php
                                    $roleClass = $user->role === 'admin' ? 'bg-danger' : 'bg-info';
                                    $roleLabel = $user->role === 'admin' ? 'Administrator' : 'Staff Member';
                                ?>
                                <span class="badge <?= $roleClass ?>"><?= $roleLabel ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $user->created ? $user->created->format('F j, Y, g:i a') : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Last Modified</th>
                            <td><?= $user->modified ? $user->modified->format('F j, Y, g:i a') : 'N/A' ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h3>Role Permissions</h3>
                    <div class="alert alert-info">
                        <?php if ($user->role === 'admin'): ?>
                            <i class="fas fa-shield-alt"></i>
                            <strong>Administrator Access:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Full access to all admin panel features</li>
                                <li>Can manage users and assign roles</li>
                                <li>Can modify all content and settings</li>
                                <li>Can view system reports and analytics</li>
                            </ul>
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                            <strong>Staff Member Access:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Can manage content (pages, news, etc.)</li>
                                <li>Can upload and manage files</li>
                                <li>Cannot manage users or system settings</li>
                                <li>Limited access to reports</li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Edit User',
                        ['action' => 'edit', $user->id],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Delete User',
                        ['action' => 'delete', $user->id],
                        [
                            'class' => 'btn btn-danger',
                            'confirm' => 'Are you sure you want to delete this user?',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>