<!-- ======= Why Us Section ======= -->
<section id="why-us" class="why-us">
  <div class="container">

    <div class="row">
      <div class="col-lg-4 d-flex align-items-stretch">
        <div class="content">
          <h3><?= h($title) ?></h3>
          <p><?= h($description) ?></p>

        </div>
      </div>
      <div class="col-lg-8 d-flex align-items-stretch">
        <div class="icon-boxes d-flex flex-column justify-content-center">
          <div class="row">
            <?php foreach ($features as $feature): ?>
            <div class="col-xl-4 d-flex align-items-stretch">
              <div class="icon-box mt-4 mt-xl-0">
                <i class="<?= h($feature['icon']) ?>"></i>
                <h4><?= h($feature['title']) ?></h4>
                <p><?= h($feature['description']) ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div><!-- End .content-->
      </div>
    </div>

  </div>
</section><!-- End Why Us Section -->
