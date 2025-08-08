<?php
/**
 * CakePHP Home Page Template
 * File: templates/Pages/home.php
 * Contains the main home page content with all sections
 */
?>

<?php $this->assign('title', 'Home'); ?>

<!-- ======= Hero Section ======= -->
<?= $this->cell('Hero') ?>

<!-- ======= Why Us Section ======= -->
<?= $this->cell('WhyUs') ?>

<!-- ======= About Section ======= -->
<?= $this->cell('About') ?>

<!-- ======= Counts Section ======= -->
<?= $this->cell('Counts') ?>

<!-- ======= Services Section ======= -->
<?= $this->cell('Services') ?>

<!-- ======= Appointment Section ======= -->
<?php //$this->cell('Appointment') ?>

<!-- ======= Departments Section ======= -->
<?= $this->cell('Departments') ?>

<!-- ======= Doctors Section ======= -->
<?= $this->cell('Doctors') ?>

<!-- ======= Frequently Asked Questions Section ======= -->
<?php // Temporarily commented out: $this->cell('Faq') ?>

<!-- ======= Testimonials Section ======= -->
<?php //$this->cell('Testimonials') ?>

<!-- ======= Gallery Section ======= -->
<?= $this->cell('Gallery') ?>