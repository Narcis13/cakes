<!-- ======= Doctors Section ======= -->
<section id="doctors" class="doctors">
  <div class="container">

    <div class="section-title">
      <h2><?= h($sectionTitle) ?></h2>
      <p><?= h($sectionDescription) ?></p>
    </div>

    <div class="row">
      <?php foreach ($doctors as $index => $doctor): ?>
      <div class="col-lg-6 <?= $index > 1 ? 'mt-4' : '' ?> <?= $index == 1 ? 'mt-4 mt-lg-0' : '' ?>">
        <div class="member d-flex align-items-start">
          <div class="pic"><img src="<?= h($doctor['image']) ?>" class="img-fluid" alt=""></div>
          <div class="member-info">
            <h4><?= h($doctor['name']) ?></h4>
            <span><?= h($doctor['position']) ?></span>
            <p><?= h($doctor['description']) ?></p>
            <div class="social">
              <a href="<?= h($doctor['social']['twitter']) ?>"><i class="ri-twitter-fill"></i></a>
              <a href="<?= h($doctor['social']['facebook']) ?>"><i class="ri-facebook-fill"></i></a>
              <a href="<?= h($doctor['social']['instagram']) ?>"><i class="ri-instagram-fill"></i></a>
              <a href="<?= h($doctor['social']['linkedin']) ?>"> <i class="ri-linkedin-box-fill"></i> </a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section><!-- End Doctors Section -->
