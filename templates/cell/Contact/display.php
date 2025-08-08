<!-- ======= Contact Section ======= -->
<section id="contact" class="contact">
  <div class="container">

    <div class="section-title">
      <h2><?= h($sectionTitle) ?></h2>

    </div>
  </div>

  <div>
    <iframe style="border:0; width: 100%; height: 350px;" src="<?= h($mapEmbedUrl) ?>" frameborder="0" allowfullscreen></iframe>
  </div>

  <div class="container">
    <div class="row mt-5">

      <div class="col-lg-4">
        <div class="info">
          <div class="address">
            <i class="bi bi-geo-alt"></i>
            <h4>Locatie</h4>
            <p>Arges, Pitesti, Str. Negru Voda nr. 47</p>
          </div>

          <div class="email">
            <i class="bi bi-envelope"></i>
            <h4>Email:</h4>
            <p><?= h($contactInfo['email']) ?></p>
          </div>

          <div class="phone">
            <i class="bi bi-phone"></i>
            <h4>Telefon:</h4>
            <p><?= h($contactInfo['phone']) ?></p>
          </div>

        </div>

      </div>

      <div class="col-lg-8 mt-5 mt-lg-0">



      </div>

    </div>

  </div>
</section><!-- End Contact Section -->
