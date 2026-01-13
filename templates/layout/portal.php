<?php
/**
 * CakePHP Patient Portal Layout
 *
 * Layout for authenticated patient pages with navigation sidebar
 */
$cakeDescription = 'Portal Pacient - SMUP';
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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Raleway:500,600,700" rel="stylesheet">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('scriptTop') ?>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f4f6f9;
        }
        .navbar-brand {
            font-family: 'Raleway', sans-serif;
            font-weight: 600;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #fff;
            border-right: 1px solid #e9ecef;
        }
        .sidebar .nav-link {
            color: #495057;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
        }
        .sidebar .nav-link:hover {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .sidebar .nav-link.active {
            background-color: #1976d2;
            color: white;
        }
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 8px;
        }
        .content {
            min-height: calc(100vh - 56px);
            padding: 1.5rem;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            border-radius: 0.5rem;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
        }
        .user-info {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
        .user-info .user-name {
            font-weight: 600;
            color: #1976d2;
        }
        .badge-status {
            font-size: 0.85em;
            padding: 0.4em 0.8em;
        }
        .btn-portal {
            background-color: #1976d2;
            border-color: #1976d2;
            color: white;
        }
        .btn-portal:hover {
            background-color: #1565c0;
            border-color: #1565c0;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1976d2;">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $this->Url->build(['controller' => 'Patients', 'action' => 'portal']) ?>">
                <i class="fas fa-hospital-user"></i>
                Portal Pacient
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portalNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="portalNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <?= $this->Html->link(
                            '<i class="fas fa-calendar-plus"></i> Programare nouă',
                            ['controller' => 'Appointments', 'action' => 'index'],
                            ['class' => 'nav-link', 'escape' => false]
                        ) ?>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <?php
                            $patient = $this->request->getAttribute('identity');
                            echo $patient ? h($patient->full_name) : 'Pacient';
                            ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <?= $this->Html->link(
                                    '<i class="fas fa-user"></i> Profilul meu',
                                    ['controller' => 'Patients', 'action' => 'profile'],
                                    ['class' => 'dropdown-item', 'escape' => false]
                                ) ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <?= $this->Html->link(
                                    '<i class="fas fa-home"></i> Pagina principală',
                                    '/',
                                    ['class' => 'dropdown-item', 'escape' => false, 'target' => '_blank']
                                ) ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <?= $this->Html->link(
                                    '<i class="fas fa-sign-out-alt"></i> Deconectare',
                                    ['controller' => 'Patients', 'action' => 'logout'],
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
                    <?php
                    $currentAction = $this->request->getParam('action');
                    ?>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-tachometer-alt"></i> Panou de control',
                                ['controller' => 'Patients', 'action' => 'portal'],
                                ['class' => 'nav-link' . ($currentAction === 'portal' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar-check"></i> Programările mele',
                                ['controller' => 'Patients', 'action' => 'appointments'],
                                ['class' => 'nav-link' . ($currentAction === 'appointments' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-calendar-plus"></i> Programare nouă',
                                ['controller' => 'Appointments', 'action' => 'index'],
                                ['class' => 'nav-link', 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<i class="fas fa-user-edit"></i> Profil',
                                ['controller' => 'Patients', 'action' => 'profile'],
                                ['class' => 'nav-link' . ($currentAction === 'profile' ? ' active' : ''), 'escape' => false]
                            ) ?>
                        </li>
                        <li class="nav-item mt-4">
                            <?= $this->Html->link(
                                '<i class="fas fa-sign-out-alt"></i> Deconectare',
                                ['controller' => 'Patients', 'action' => 'logout'],
                                ['class' => 'nav-link text-danger', 'escape' => false]
                            ) ?>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
