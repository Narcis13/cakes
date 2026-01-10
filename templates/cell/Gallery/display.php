<!-- ======= Gallery Section ======= -->
<section id="gallery" class="gallery">
  <div class="container">

    <div class="section-title">
      <h2><?= h($sectionTitle) ?></h2>
      <p><?= h($sectionDescription) ?></p>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row g-0">

      <?php foreach ($galleryItems as $item): ?>
      <div class="col-lg-3 col-md-4">
        <div class="gallery-item">
          <a href="<?= h($item['url']) ?>" class="galelry-lightbox" data-glightbox="title: <?= h($item['title'] ?? '') ?>">
            <img src="<?= h($item['url']) ?>" alt="<?= h($item['alt'] ?? '') ?>" class="img-fluid">
          </a>
        </div>
      </div>
      <?php endforeach; ?>

    </div>

  </div>
</section><!-- End Gallery Section -->
