<!-- ======= Doctors Section ======= -->
<section id="doctors" class="doctors">
  <div class="container">

    <div class="section-title">
      <h2><?= h($sectionTitle) ?></h2>
      <p><?= h($sectionDescription) ?></p>
    </div>

    <?php if (!empty($doctorSlides) && $totalSlides > 1): ?>
    <!-- Doctors Carousel -->
    <div id="doctorsCarousel" class="carousel slide" data-bs-ride="carousel">
      <!-- Carousel Indicators -->
      <div class="carousel-indicators">
        <?php for ($i = 0; $i < $totalSlides; $i++): ?>
        <button type="button" data-bs-target="#doctorsCarousel" data-bs-slide-to="<?= $i ?>" 
                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?> 
                aria-label="Slide <?= $i + 1 ?>"></button>
        <?php endfor; ?>
      </div>

      <!-- Carousel Inner -->
      <div class="carousel-inner">
        <?php foreach ($doctorSlides as $slideIndex => $slide): ?>
        <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
          <div class="row">
            <?php foreach ($slide as $index => $doctor): ?>
            <div class="col-lg-3 col-md-6 <?= $index > 0 ? 'mt-4 mt-lg-0' : '' ?>">
              <div class="member d-flex flex-column align-items-center text-center">
                <div class="pic mb-3">
                  <?php if ($doctor['hasPhoto']): ?>
                    <img src="<?= h($doctor['image']) ?>" class="img-fluid rounded-circle" alt="<?= h($doctor['name']) ?>" style="width: 120px; height: 120px; object-fit: cover;">
                  <?php else: ?>
                    <div class="initials-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background-color: #007bff; color: white; font-size: 24px; font-weight: bold;">
                      <?= h($doctor['initials']) ?>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="member-info">
                  <h4><?= h($doctor['name']) ?></h4>
                  <span class="text-muted"><?= h($doctor['position']) ?></span>
                  <?php if (!empty($doctor['department'])): ?>
                    <div class="department mb-2">
                      <small class="badge bg-primary"><?= h($doctor['department']) ?></small>
                    </div>
                  <?php endif; ?>
                  <p class="small"><?= h(substr($doctor['description'], 0, 100)) ?><?= strlen($doctor['description']) > 100 ? '...' : '' ?></p>
                  <?php if (!empty($doctor['experience'])): ?>
                    <div class="experience mb-2">
                      <small class="text-success"><strong><?= h($doctor['experience']) ?></strong> years experience</small>
                    </div>
                  <?php endif; ?>
                  <div class="social">
                    <?php if (!empty($doctor['social']['twitter'])): ?>
                      <a href="<?= h($doctor['social']['twitter']) ?>" target="_blank"><i class="ri-twitter-fill"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($doctor['social']['facebook'])): ?>
                      <a href="<?= h($doctor['social']['facebook']) ?>" target="_blank"><i class="ri-facebook-fill"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($doctor['social']['instagram'])): ?>
                      <a href="<?= h($doctor['social']['instagram']) ?>" target="_blank"><i class="ri-instagram-fill"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($doctor['social']['linkedin'])): ?>
                      <a href="<?= h($doctor['social']['linkedin']) ?>" target="_blank"><i class="ri-linkedin-box-fill"></i></a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#doctorsCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#doctorsCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
    <?php elseif (!empty($doctors)): ?>
    <!-- Static Grid (when only one slide or no slides) -->
    <div class="row">
      <?php foreach (array_slice($doctors, 0, 4) as $index => $doctor): ?>
      <div class="col-lg-3 col-md-6 <?= $index > 0 ? 'mt-4 mt-lg-0' : '' ?>">
        <div class="member d-flex flex-column align-items-center text-center">
          <div class="pic mb-3">
            <?php if ($doctor['hasPhoto']): ?>
              <img src="<?= h($doctor['image']) ?>" class="img-fluid rounded-circle" alt="<?= h($doctor['name']) ?>" style="width: 120px; height: 120px; object-fit: cover;">
            <?php else: ?>
              <div class="initials-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background-color: #007bff; color: white; font-size: 24px; font-weight: bold;">
                <?= h($doctor['initials']) ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="member-info">
            <h4><?= h($doctor['name']) ?></h4>
            <span class="text-muted"><?= h($doctor['position']) ?></span>
            <?php if (!empty($doctor['department'])): ?>
              <div class="department mb-2">
                <small class="badge bg-primary"><?= h($doctor['department']) ?></small>
              </div>
            <?php endif; ?>
            <p class="small"><?= h(substr($doctor['description'], 0, 100)) ?><?= strlen($doctor['description']) > 100 ? '...' : '' ?></p>
            <?php if (!empty($doctor['experience'])): ?>
              <div class="experience mb-2">
                <small class="text-success"><strong><?= h($doctor['experience']) ?></strong> years experience</small>
              </div>
            <?php endif; ?>
            <div class="social">
              <?php if (!empty($doctor['social']['twitter'])): ?>
                <a href="<?= h($doctor['social']['twitter']) ?>" target="_blank"><i class="ri-twitter-fill"></i></a>
              <?php endif; ?>
              <?php if (!empty($doctor['social']['facebook'])): ?>
                <a href="<?= h($doctor['social']['facebook']) ?>" target="_blank"><i class="ri-facebook-fill"></i></a>
              <?php endif; ?>
              <?php if (!empty($doctor['social']['instagram'])): ?>
                <a href="<?= h($doctor['social']['instagram']) ?>" target="_blank"><i class="ri-instagram-fill"></i></a>
              <?php endif; ?>
              <?php if (!empty($doctor['social']['linkedin'])): ?>
                <a href="<?= h($doctor['social']['linkedin']) ?>" target="_blank"><i class="ri-linkedin-box-fill"></i></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="row">
      <div class="col-12 text-center">
        <p class="text-muted">No doctors available at the moment.</p>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section><!-- End Doctors Section -->
