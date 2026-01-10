<?php
/**
 * Navbar Cell Template
 * File: templates/cell/Navbar/display.php
 */
?>



<nav class="navbar navbar-expand-lg hospital-navbar">
    <div class="container">
        <?= $this->Html->link(
            '<i class="fas fa-hospital me-2"></i>Spitalul Militar Pitesti',
            '/',
            ['class' => 'navbar-brand', 'escape' => false]
        ) ?>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php foreach ($menuItems as $label => $url): ?>
                    <li class="nav-item">
                        <?= $this->Html->link(
                            $label, 
                            $url, 
                            ['class' => 'nav-link' . ($currentPage === $url ? ' active' : '')]
                        ) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="d-flex">
                <?= $this->Html->link(
                    '<i class="fas fa-calendar-check me-2"></i>Book Appointment',
                    '/appointments',
                    ['class' => 'btn btn-appointment text-white', 'escape' => false]
                ) ?>
            </div>
        </div>
    </div>
</nav>