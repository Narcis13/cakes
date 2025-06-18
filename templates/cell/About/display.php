<!-- ======= About Section ======= -->
<section id="about" class="about">
  <div class="container-fluid">

    <div class="row">
      <div class="col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch position-relative">
        <a href="<?= h($videoUrl) ?>" class="glightbox play-btn mb-4"></a>
      </div>

      <div class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
        <h3><?= h($title) ?></h3>
        <p><?= h($description) ?></p>

        <?php foreach ($features as $feature): ?>
        <div class="icon-box">
          <div class="icon"><i class="<?= h($feature['icon']) ?>"></i></div>
          <h4 class="title"><a href=""><?= h($feature['title']) ?></a></h4>
          <p class="description"><?= h($feature['description']) ?></p>
        </div>
        <?php endforeach; ?>

      </div>
    </div>

  </div>
</section><!-- End About Section -->
