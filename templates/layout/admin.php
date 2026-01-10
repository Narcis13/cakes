<?php
/**
 * CakePHP Admin Layout
 */
$cakeDescription = 'SMUP Admin';
?>
<!DOCTYPE html>
<html lang="ro">
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
                SMUP Admin
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
                            echo $user ? h($user->email) : 'Utilizator';
                            ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <?= $this->Html->link(
                                    '<i class="fas fa-home"></i> Vizualizează Site',
                                    '/',
                                    ['class' => 'dropdown-item', 'escape' => false, 'target' => '_blank']
                                ) ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <?= $this->Html->link(
                                    '<i class="fas fa-sign-out-alt"></i> Deconectare',
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
                                '<i class="fas fa-tachometer-alt"></i> Panou de control',
                                ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Dashboard' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">Gestionare Utilizatori</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-users"></i> Utilizatori',
                                ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Users' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">Gestionare Spital</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-building"></i> Departamente',
                                ['prefix' => 'Admin', 'controller' => 'Departments', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Departments' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-user-md"></i> Personal',
                                ['prefix' => 'Admin', 'controller' => 'Staff', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Staff' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-stethoscope"></i> Specializări',
                                ['prefix' => 'Admin', 'controller' => 'Specializations', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Specializations' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar-times"></i> Indisponibilități Personal',
                                ['prefix' => 'Admin', 'controller' => 'StaffUnavailabilities', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'StaffUnavailabilities' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar-alt"></i> Sărbători Spital',
                                ['prefix' => 'Admin', 'controller' => 'HospitalHolidays', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'HospitalHolidays' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-stethoscope"></i> Servicii',
                                ['prefix' => 'Admin', 'controller' => 'Services', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Services' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar"></i> Program Medici',
                                ['prefix' => 'Admin', 'controller' => 'DoctorSchedules', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'DoctorSchedules' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar-plus"></i> Excepții Program',
                                ['prefix' => 'Admin', 'controller' => 'ScheduleExceptions', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'ScheduleExceptions' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar-check"></i> Programări',
                                ['prefix' => 'Admin', 'controller' => 'Appointments', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Appointments' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-envelope"></i> Mesaje',
                                ['prefix' => 'Admin', 'controller' => 'ContactMessages', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'ContactMessages' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">Gestionare Conținut</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-newspaper"></i> Știri',
                                ['prefix' => 'Admin', 'controller' => 'News', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'News' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-folder-open"></i> Categorii Știri',
                                ['prefix' => 'Admin', 'controller' => 'NewsCategories', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'NewsCategories' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-file-alt"></i> Pagini',
                                ['prefix' => 'Admin', 'controller' => 'Pages', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Pages' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-bars"></i> Meniu Navigare',
                                ['prefix' => 'Admin', 'controller' => 'NavbarItems', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'NavbarItems' ? ' active' : ''), 'escape' => false]
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
                            <?= $this->Html->link(
                                '<i class="fas fa-file-alt"></i> Manager Fișiere',
                                ['prefix' => 'Admin', 'controller' => 'Files', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Files' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">Automatizare</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-project-diagram"></i> Fluxuri de lucru',
                                ['prefix' => 'Admin', 'controller' => 'Workflows', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'Workflows' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-play-circle"></i> Execuții',
                                ['prefix' => 'Admin', 'controller' => 'WorkflowExecutions', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'WorkflowExecutions' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-tasks"></i> Sarcini Manuale',
                                ['prefix' => 'Admin', 'controller' => 'WorkflowHumanTasks', 'action' => 'index'],
                                ['class' => 'nav-link' . ($this->request->getParam('controller') === 'WorkflowHumanTasks' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link text-muted small text-uppercase">Sistem</span>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-cog"></i> Setări',
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