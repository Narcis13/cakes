<!-- ======= Top Bar ======= -->
<div id="topbar" class="d-flex align-items-center fixed-top">
  <div class="container d-flex justify-content-between">
    <div class="contact-info d-flex align-items-center">
      <i class="bi bi-envelope"></i> <a href="mailto:<?= h($contactEmail) ?>"><?= h($contactEmail) ?></a>
      <i class="bi bi-phone"></i> <?= h($contactPhone) ?>
    </div>
    <div class="d-none d-lg-flex social-links align-items-center">
      <a href="<?= h($socialLinks['youtube']) ?>" class="twitter"><i class="bi bi-youtube"></i></a>
      <a href="<?= h($socialLinks['facebook']) ?>" class="facebook"><i class="bi bi-facebook"></i></a>
      <a href="<?= h($socialLinks['instagram']) ?>" class="instagram"><i class="bi bi-instagram"></i></a>
      <a href="<?= h($socialLinks['linkedin']) ?>" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
    </div>
  </div>
</div>