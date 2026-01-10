<?php
/**
 * CakePHP Index Page Template
 * File: templates/Pages/index.php
 * Alternative home page using cells structure
 */
?>

<?php $this->assign('title', 'Welcome to Spitalul Militar Pitesti'); ?>

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
<?= $this->cell('Appointment') ?>

<!-- ======= Departments Section ======= -->
<?= $this->cell('Departments') ?>

<!-- ======= Doctors Section ======= -->
<?= $this->cell('Doctors') ?>

<!-- ======= Frequently Asked Questions Section ======= -->
<?php // Temporarily commented out: $this->cell('Faq') ?>

<!-- ======= Testimonials Section ======= -->
<?= $this->cell('Testimonials') ?>

