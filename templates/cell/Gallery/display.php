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

      <?php foreach ($galleryItems as $image): ?>
      <div class="col-lg-3 col-md-4">
        <div class="gallery-item">
          <a href="<?= h($image) ?>" class="galelry-lightbox">
            <img src="<?= h($image) ?>" alt="" class="img-fluid">
          </a>
        </div>
      </div>
      <?php endforeach; ?>

    </div>

  </div>
</section><!-- End Gallery Section -->
