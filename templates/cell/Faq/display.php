<!-- ======= Frequently Asked Questions Section ======= -->
<section id="faq" class="faq section-bg">
  <div class="container">

    <div class="section-title">
      <h2><?= h($sectionTitle) ?></h2>
      <p><?= h($sectionDescription) ?></p>
    </div>

    <div class="faq-list">
      <ul>
        <?php foreach ($faqs as $faq): ?>
        <li data-aos="fade-up" <?= $faq['delay'] > 0 ? 'data-aos-delay="' . $faq['delay'] . '"' : '' ?>>
          <i class="bx bx-help-circle icon-help"></i> 
          <a data-bs-toggle="collapse" class="<?= $faq['show'] ? 'collapse' : 'collapse collapsed' ?>" data-bs-target="#<?= h($faq['id']) ?>">
            <?= h($faq['question']) ?> 
            <i class="bx bx-chevron-down icon-show"></i>
            <i class="bx bx-chevron-up icon-close"></i>
          </a>
          <div id="<?= h($faq['id']) ?>" class="collapse <?= $faq['show'] ? 'show' : '' ?>" data-bs-parent=".faq-list">
            <p><?= h($faq['answer']) ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</section><!-- End Frequently Asked Questions Section -->
