<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $stats
 */
$this->assign('title', 'Dashboard');
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-3">
            <i class="fas fa-tachometer-alt"></i>
            Admin Dashboard
        </h1>
        <p class="lead">Welcome back, <?= h($user->email) ?>! Here's an overview of your hospital management system.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-check fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0"><?= number_format($stats['total_appointments'] ?? 0) ?></h5>
                        <p class="card-text">Total Appointments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-day fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0"><?= number_format($stats['today_appointments'] ?? 0) ?></h5>
                        <p class="card-text">Today's Appointments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-md fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0"><?= number_format($stats['total_staff'] ?? 0) ?></h5>
                        <p class="card-text">Staff Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-building fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0"><?= number_format($stats['total_departments'] ?? 0) ?></h5>
                        <p class="card-text">Departments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-users"></i> Manage Users',
                            ['controller' => 'Users', 'action' => 'index'],
                            ['class' => 'btn btn-outline-primary btn-sm w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-cog"></i> Settings',
                            ['controller' => 'Settings', 'action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-sm w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-user-md"></i> Staff',
                            ['controller' => 'Staff', 'action' => 'index'],
                            ['class' => 'btn btn-outline-info btn-sm w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-building"></i> Departments',
                            ['controller' => 'Departments', 'action' => 'index'],
                            ['class' => 'btn btn-outline-warning btn-sm w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-newspaper"></i> News',
                            ['controller' => 'News', 'action' => 'index'],
                            ['class' => 'btn btn-outline-success btn-sm w-100', 'escape' => false]
                        ) ?>
                    </div>
                    <div class="col-md-2 mb-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-home"></i> View Site',
                            '/',
                            ['class' => 'btn btn-outline-dark btn-sm w-100', 'escape' => false, 'target' => '_blank']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent News -->
<?php if (!empty($stats['recent_news'])): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-newspaper"></i>
                    Recent News
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['recent_news'] as $news): ?>
                            <tr>
                                <td><?= h($news->title) ?></td>
                                <td><?= $news->created->format('M d, Y') ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['controller' => 'News', 'action' => 'view', $news->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['controller' => 'News', 'action' => 'edit', $news->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Edit']
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>