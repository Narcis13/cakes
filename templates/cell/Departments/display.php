<!-- ======= Departments Section ======= -->
<section id="departments" class="departments">
  <div class="container">

    <div class="section-title">
      <h2><?= h($sectionTitle) ?></h2>
      <p><?= h($sectionDescription) ?></p>
    </div>

    <div class="row gy-4">
      <div class="col-lg-3">
        <ul class="nav nav-tabs flex-column">
          <?php foreach ($departments as $department): ?>
          <li class="nav-item">
            <a class="nav-link <?= $department['active'] ? 'active show' : '' ?>" data-bs-toggle="tab" href="#<?= h($department['id']) ?>"><?= h($department['name']) ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="col-lg-9">
        <div class="tab-content">
          <?php foreach ($departments as $department): ?>
          <div class="tab-pane <?= $department['active'] ? 'active show' : '' ?>" id="<?= h($department['id']) ?>">
            <div class="row gy-4">
              <div class="col-lg-8 details order-2 order-lg-1">
                <h3><?= h($department['title']) ?></h3>
                <p class="fst-italic"><?= h($department['subtitle']) ?></p>
                <p><?= h($department['description']) ?></p>
              </div>
              <div class="col-lg-4 text-center order-1 order-lg-2">
                <img src="<?= h($department['image']) ?>" alt="" class="img-fluid">
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div>
</section><!-- End Departments Section -->
