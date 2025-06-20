<?php
/**
 * CakePHP Medilab Layout
 * File: templates/layout/default.php
 * Based on Medilab Bootstrap Template
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->Html->charset() ?>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>
        <?= $cakeDescription ?? 'Medilab' ?>:
        <?= $this->fetch('title') ?>
    </title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->meta('apple-touch-icon', '/img/apple-touch-icon.png') ?>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <?= $this->Html->css('/vendor/fontawesome-free/css/all.min.css') ?>
    <?= $this->Html->css('/vendor/animate.css/animate.min.css') ?>
    <?= $this->Html->css('/vendor/bootstrap/css/bootstrap.min.css') ?>
    <?= $this->Html->css('/vendor/bootstrap-icons/bootstrap-icons.css') ?>
    <?= $this->Html->css('/vendor/boxicons/css/boxicons.min.css') ?>
    <?= $this->Html->css('/vendor/glightbox/css/glightbox.min.css') ?>
    <?= $this->Html->css('/vendor/remixicon/remixicon.css') ?>
    <?= $this->Html->css('/vendor/swiper/swiper-bundle.min.css') ?>

    <!-- Template Main CSS File -->
    <?= $this->Html->css('style.css') ?>
    
    <!-- Layout Fix for Header Overlap -->
    <style>
        /* Fix for header overlap issue */
        #main {
            padding-top: 110px; /* TopBar (40px) + Header (approx 70px) */
        }
        
        /* For pages with Hero section - full height hero should start from top */
        #main #hero {
            margin-top: -110px; /* Pull hero back up to start from header */
            padding-top: 200px; /* Add enough padding for header + hero content spacing */
        }
        
        /* For pages without Hero section - ensure proper spacing */
        #main > section:first-child:not(#hero) {
            margin-top: 20px; /* Extra space for non-hero first sections */
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            #main {
                padding-top: 90px;
            }
            
            #main #hero {
                margin-top: -90px;
                padding-top: 150px;
            }
        }
    </style>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <!-- ======= Top Bar ======= -->

  <!-- ======= Top Bar ======= -->
  <?= $this->cell('TopBar') ?>

  <!-- ======= Header ======= -->
  <?= $this->cell('Header') ?>



    <!-- Flash Messages -->
    <?= $this->Flash->render() ?>

    <!-- Main Content -->
    <main id="main">
        <?= $this->fetch('content') ?>
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?= $this->cell('Footer') ?>

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?= $this->Html->script('/vendor/purecounter/purecounter_vanilla.js') ?>
    <?= $this->Html->script('/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>
    <?= $this->Html->script('/vendor/glightbox/js/glightbox.min.js') ?>
    <?= $this->Html->script('/vendor/swiper/swiper-bundle.min.js') ?>
    <?= $this->Html->script('/vendor/php-email-form/validate.js') ?>

    <!-- Template Main JS File -->
    <?= $this->Html->script('main.js') ?>
    <?= $this->fetch('scriptBottom') ?>
</body>

</html>