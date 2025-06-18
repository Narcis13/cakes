<!-- ======= Services Section ======= -->
<section id="services" class="services">
  <div class="container">

    <div class="section-title">
      <h2><?= h($sectionTitle) ?></h2>
      <p><?= h($sectionDescription) ?></p>
    </div>

    <div class="row">
      <?php foreach ($services as $index => $service): ?>
      <div class="col-lg-4 col-md-6 d-flex align-items-stretch <?= $index > 0 ? 'mt-4' : '' ?> <?= $index == 1 ? 'mt-md-0' : '' ?> <?= $index == 2 ? 'mt-lg-0' : '' ?>">
        <div class="icon-box">
          <div class="icon"><i class="<?= h($service['icon']) ?>"></i></div>
          <h4><a href=""><?= h($service['title']) ?></a></h4>
          <p><?= h($service['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section><!-- End Services Section -->
