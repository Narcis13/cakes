<!-- ======= Counts Section ======= -->
<section id="counts" class="counts">
  <div class="container">

    <div class="row">
      <?php foreach ($counts as $count): ?>
      <div class="col-lg-3 col-md-6 <?= $count === reset($counts) ? '' : 'mt-5 mt-md-0' ?> <?= array_slice($counts, 2)[0] === $count ? 'mt-5 mt-lg-0' : '' ?> <?= end($counts) === $count ? 'mt-5 mt-lg-0' : '' ?>">
        <div class="count-box">
          <i class="<?= h($count['icon']) ?>"></i>
          <span data-purecounter-start="0" data-purecounter-end="<?= h($count['number']) ?>" data-purecounter-duration="1" class="purecounter"></span>
          <p><?= h($count['label']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section><!-- End Counts Section -->
