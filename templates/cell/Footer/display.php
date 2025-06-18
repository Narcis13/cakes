<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-contact">
                    <h3><?= h($contactInfo['name']) ?></h3>
                    <p>
                        <?= h($contactInfo['address']) ?> <br>
                        <?= h($contactInfo['city']) ?><br>
                        <?= h($contactInfo['country']) ?> <br><br>
                        <strong>Phone:</strong> <?= h($contactInfo['phone']) ?><br>
                        <strong>Email:</strong> <?= h($contactInfo['email']) ?><br>
                    </p>
                </div>

                <div class="col-lg-2 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <?php foreach ($usefulLinks as $link): ?>
                            <li><i class="bx bx-chevron-right"></i> <?= $this->Html->link(h($link['title']), $link['url']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <?php foreach ($serviceLinks as $link): ?>
                            <li><i class="bx bx-chevron-right"></i> <a href="<?= h($link['url']) ?>"><?= h($link['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 footer-newsletter">
                    <h4>Join Our Newsletter</h4>
                    <p><?= h($newsletterText) ?></p>
                    <form action="" method="post">
                        <input type="email" name="email"><input type="submit" value="Subscribe">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container d-md-flex py-4">
        <div class="me-md-auto text-center text-md-start">
            <div class="copyright">
                <?= $copyright ?>
            </div>
            <div class="credits">
                <?= $credits ?>
            </div>
        </div>
        <div class="social-links text-center text-md-right pt-3 pt-md-0">
            <a href="<?= h($socialLinks['twitter']) ?>" class="twitter"><i class="bx bxl-twitter"></i></a>
            <a href="<?= h($socialLinks['facebook']) ?>" class="facebook"><i class="bx bxl-facebook"></i></a>
            <a href="<?= h($socialLinks['instagram']) ?>" class="instagram"><i class="bx bxl-instagram"></i></a>
            <a href="<?= h($socialLinks['skype']) ?>" class="google-plus"><i class="bx bxl-skype"></i></a>
            <a href="<?= h($socialLinks['linkedin']) ?>" class="linkedin"><i class="bx bxl-linkedin"></i></a>
        </div>
    </div>
</footer><!-- End Footer -->
