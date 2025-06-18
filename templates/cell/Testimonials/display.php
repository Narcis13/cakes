<!-- ======= Testimonials Section ======= -->
<section id="testimonials" class="testimonials">
  <div class="container">

    <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper-wrapper">

        <?php foreach ($testimonials as $testimonial): ?>
        <div class="swiper-slide">
          <div class="testimonial-wrap">
            <div class="testimonial-item">
              <img src="<?= h($testimonial['image']) ?>" class="testimonial-img" alt="">
              <h3><?= h($testimonial['name']) ?></h3>
              <h4><?= h($testimonial['position']) ?></h4>
              <p>
                <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                <?= h($testimonial['quote']) ?>
                <i class="bx bxs-quote-alt-right quote-icon-right"></i>
              </p>
            </div>
          </div>
        </div><!-- End testimonial item -->
        <?php endforeach; ?>

      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>
</section><!-- End Testimonials Section -->
