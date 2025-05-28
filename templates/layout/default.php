<?php
/**
 * CakePHP Hospital Layout
 * File: templates/layout/default.php
 */
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

    <!-- Bootstrap CSS -->
    <?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css') ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --hospital-primary: #2c5aa0;
            --hospital-secondary: #4a90e2;
            --hospital-accent: #e8f4fd;
            --hospital-success: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        
        .navbar-hospital {
            background: linear-gradient(135deg, var(--hospital-primary), var(--hospital-secondary));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
            transform: translateY(-1px);
        }
        
        .emergency-banner {
            background: linear-gradient(45deg, #dc3545, #e74c3c);
            color: white;
            padding: 10px 0;
            text-align: center;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }
        
        main {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
        
        .footer-hospital {
            background: var(--hospital-primary);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: auto;
        }
        
        .footer-section h5 {
            color: var(--hospital-accent);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-section ul li a:hover {
            color: var(--hospital-accent);
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            color: var(--hospital-accent);
            transform: translateY(-2px);
        }
        
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.2);
            margin-top: 2rem;
            padding-top: 1rem;
            text-align: center;
            color: rgba(255,255,255,0.8);
        }
    </style>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <!-- Emergency Banner -->
    <div class="emergency-banner">
        <div class="container">
            <i class="fas fa-ambulance me-2"></i>
            Emergency? Call 911 | 24/7 Emergency Services Available
            <i class="fas fa-phone-alt ms-2"></i>
        </div>
    </div>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-hospital">
        <div class="container">
            <?= $this->Html->link(
                '<i class="fas fa-hospital me-2"></i>City General Hospital',
                '/',
                ['class' => 'navbar-brand text-white', 'escape' => false]
            ) ?>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <?= $this->Html->link(
                            '<i class="fas fa-home me-1"></i>Home',
                            '/',
                            ['class' => 'nav-link px-3', 'escape' => false]
                        ) ?>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-stethoscope me-1"></i>Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-heart me-2"></i>Cardiology</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-brain me-2"></i>Neurology</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-baby me-2"></i>Pediatrics</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-x-ray me-2"></i>Radiology</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link(
                            '<i class="fas fa-user-md me-1"></i>Doctors',
                            '/doctors',
                            ['class' => 'nav-link px-3', 'escape' => false]
                        ) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link(
                            '<i class="fas fa-calendar-alt me-1"></i>Appointments',
                            '/appointments',
                            ['class' => 'nav-link px-3', 'escape' => false]
                        ) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link(
                            '<i class="fas fa-envelope me-1"></i>Contact',
                            '/contact',
                            ['class' => 'nav-link px-3', 'escape' => false]
                        ) ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?= $this->Flash->render() ?>

    <!-- Main Content -->
    <main>
        <div class="container">
            <?= $this->fetch('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-hospital">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 footer-section">
                    <h5><i class="fas fa-hospital me-2"></i>City General Hospital</h5>
                    <p>Providing exceptional healthcare services to our community for over 50 years. Your health is our priority.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 footer-section">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><?= $this->Html->link('About Us', '/about') ?></li>
                        <li><?= $this->Html->link('Services', '/services') ?></li>
                        <li><?= $this->Html->link('Doctors', '/doctors') ?></li>
                        <li><?= $this->Html->link('Careers', '/careers') ?></li>
                        <li><?= $this->Html->link('News', '/news') ?></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 footer-section">
                    <h5>Patient Services</h5>
                    <ul>
                        <li><?= $this->Html->link('Online Appointments', '/appointments') ?></li>
                        <li><?= $this->Html->link('Patient Portal', '/portal') ?></li>
                        <li><?= $this->Html->link('Insurance', '/insurance') ?></li>
                        <li><?= $this->Html->link('Medical Records', '/records') ?></li>
                        <li><?= $this->Html->link('Billing', '/billing') ?></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 footer-section">
                    <h5>Contact Info</h5>
                    <ul>
                        <li><i class="fas fa-map-marker-alt me-2"></i>123 Medical Center Dr<br>Healthcare City, HC 12345</li>
                        <li><i class="fas fa-phone me-2"></i>Main: (555) 123-4567</li>
                        <li><i class="fas fa-ambulance me-2"></i>Emergency: 911</li>
                        <li><i class="fas fa-envelope me-2"></i>info@citygeneralhospital.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; <?= date('Y') ?> City General Hospital. All rights reserved. | Privacy Policy | Terms of Service</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>