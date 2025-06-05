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

    <!-- Using Bootstrap for basic styling (assuming you have it or can add it) -->
    <!-- If you use the same vendor CSS as frontend, ensure paths are correct or include it here -->
    <?= $this->Html->css(['/vendor/bootstrap/css/bootstrap.min.css', 'normalize.min', 'cake', 'admin_custom']) // Add an admin_custom.css for specific styles ?>
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('scriptTop') ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= $this->Url->build(['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index']) ?>">Medilab Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <?= $this->Html->link('Settings', ['prefix' => 'Admin', 'controller' => 'Settings', 'action' => 'index'], ['class' => 'nav-link']) ?>
                    </li>
                    <!-- Add more admin links here -->
                    <li class="nav-item">
                        <?= $this->Html->link('Main Site', '/', ['class' => 'nav-link', 'target' => '_blank']) ?>
                    </li>
                    <!-- You would add a logout link here once auth is implemented -->
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <div class="row">
            <div class="col-12">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </main>

    <footer class="container mt-5 mb-3 text-center">
        <p>© <?= date('Y') ?> Medilab Admin. All rights reserved.</p>
    </footer>

    <?= $this->Html->script(['/vendor/bootstrap/js/bootstrap.bundle.min.js']) ?>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>