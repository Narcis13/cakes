<?php
/**
 * CakePHP Admin Layout
 */
$cakeDescription = 'Medilab Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('scriptTop') ?>
    
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        .sidebar .nav-link {
            color: #495057;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        .content {
            min-height: calc(100vh - 56px);
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $this->Url->build(['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index']) ?>">
                <i class="fas fa-hospital"></i>
                Medilab Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                            <?php
                            $user = $this->request->getAttribute('identity');
                            echo $user ? h($user->email) : 'User';
                            ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <?= $this->Html->link(
                                    '<i class="fas fa-home"></i> View Site',
                                    '/',
                                    ['class' => 'dropdown-item', 'escape' => false, 'target' => '_blank']
                                ) ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <?= $this->Html->link(
                                    '<i class="fas fa-sign-out-alt"></i> Logout',
                                    ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'logout'],
                                    ['class' => 'dropdown-item', 'escape' => false]
                                ) ?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-tachometer-alt"></i> Dashboard',
                                ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Dashboard' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">User Management</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-users"></i> Users',
                                ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Users' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">Hospital Management</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-building"></i> Departments',
                                ['prefix' => 'Admin', 'controller' => 'Departments', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Departments' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-user-md"></i> Staff',
                                ['prefix' => 'Admin', 'controller' => 'Staff', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Staff' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-stethoscope"></i> Services',
                                ['prefix' => 'Admin', 'controller' => 'Services', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Services' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar-check"></i> Appointments',
                                ['prefix' => 'Admin', 'controller' => 'Appointments', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Appointments' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">Content Management</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-newspaper"></i> News',
                                ['prefix' => 'Admin', 'controller' => 'News', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'News' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-file-alt"></i> Pages',
                                ['prefix' => 'Admin', 'controller' => 'Pages', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Pages' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-images"></i> Media',
                                ['prefix' => 'Admin', 'controller' => 'Media', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Media' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">System</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-cog"></i> Settings',
                                ['prefix' => 'Admin', 'controller' => 'Settings', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Settings' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="pt-3">
                    <?= $this->Flash->render() ?>
                    <?= $this->fetch('content') ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>