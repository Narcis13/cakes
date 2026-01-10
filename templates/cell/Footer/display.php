<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-contact">
                    <h4>Spitalul Militar de Urgenta Dr. Ion Jianu Pitesti</h4>
                    <p>
                        Str. Negru Voda nr. 47 <br>
                        Pitesti<br>
                        Arges <br><br>
                        <strong>Telefon:</strong> 0248218090<br>
                        <strong>Email:</strong> smupitesti@mapn.ro<br>
                    </p>
                </div>

                <div class="col-lg-2 col-md-6 footer-links">
                    <h4>Linkuri utile</h4>
                    <ul>
                        <?php foreach ($usefulLinks as $link): ?>
                            <li><i class="bx bx-chevron-right"></i> <?= $this->Html->link(h($link['title']), $link['url']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Serviciile noastre</h4>
                    <ul>
                        <?php foreach ($serviceLinks as $link): ?>
                            <li><i class="bx bx-chevron-right"></i> <a href="<?= h($link['url']) ?>"><?= h($link['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 footer-logos">
                    <h4>Parteneri</h4>
                    <div class="partner-logos d-flex flex-column align-items-center gap-3">
                        <img src="/img/logo-certmil.jpg" alt="CERTMIL Logo" class="img-fluid" style="max-height: 60px;">
                        <img src="/img/logo-mapn.jpg" alt="MAPN Logo" class="img-fluid" style="max-height: 60px;">
                        <img src="/img/logo-opnaj.jpg" alt="OPSNAJ Logo" class="img-fluid" style="max-height: 60px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container d-md-flex py-4">
        <div class="me-md-auto text-center text-md-start">
            <div class="copyright">
             © Copyright Spitalul Militar de Urgenta Dr. ion Jianu Pitesti. Toate drepturile rezervate.
            </div>
            <div class="credits">
                <?= $this->Html->link('Politica de Confidențialitate', '/politica-de-confidentialitate') ?> | 
                <?= $this->Html->link('Termeni și Condiții', '/terms') ?>
            </div>
        </div>
        <div class="social-links text-center text-md-right pt-3 pt-md-0">

            <a href="<?= h($socialLinks['facebook']) ?>" target="_blank" class="facebook"><i class="bx bxl-facebook"></i></a>

        </div>
    </div>
</footer><!-- End Footer -->
