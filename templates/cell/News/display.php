<!-- ======= News Section ======= -->
<section id="news" class="about">
  <div class="container-fluid">

    <div class="row">
      <div class="col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch position-relative">
        <a href="<?= h($videoUrl) ?>" class="glightbox play-btn mb-4"></a>
      </div>

      <div class="col-xl-7 col-lg-6 d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
        <h3><?= h($title) ?></h3>
        <p><?= h($description) ?></p>

        <div class="row">
          <?php foreach ($news as $article): ?>
          <div class="col-md-6 mb-4">
            <a href="/news/<?= h($article->slug) ?>" target="_blank" rel="noopener" class="news-card-link">
              <div class="news-card h-100">
                <?php if ($article->featured_image): ?>
                <div class="news-image">
                  <img src="<?= h($article->featured_image) ?>" alt="<?= h($article->title) ?>" class="img-fluid">
                </div>
                <?php endif; ?>
                <div class="news-content p-3">
                  <h5 class="news-title">
                    <?= h($article->title) ?>
                  </h5>
                  <?php if ($article->excerpt): ?>
                  <p class="news-excerpt"><?= h($article->excerpt) ?></p>
                  <?php endif; ?>
                  <div class="news-meta">
                    <small class="text-muted">
                      <?= $article->publish_date ? $article->publish_date->format('d.m.Y') : $article->created->format('d.m.Y') ?>
                    </small>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>

        <?php if (empty($news)): ?>
        <div class="text-center py-4">
          <p class="text-muted">Nu există noutăți publicate momentan.</p>
        </div>
        <?php endif; ?>

      </div>
    </div>

  </div>
</section><!-- End News Section -->

<style>
.news-card-link {
  text-decoration: none;
  color: inherit;
  display: block;
}

.news-card-link:hover {
  text-decoration: none;
  color: inherit;
}

.news-card {
  border: 1px solid #e9ecef;
  border-radius: 8px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background: #fff;
  overflow: hidden;
  cursor: pointer;
}

.news-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.news-image {
  height: 150px;
  overflow: hidden;
}

.news-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.news-title a {
  color: #2c4964;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
  line-height: 1.3;
}

.news-title a:hover {
  color: #1977cc;
}

.news-excerpt {
  font-size: 0.9rem;
  color: #666;
  margin: 10px 0;
  line-height: 1.4;
}

.news-meta {
  margin-top: auto;
  padding-top: 10px;
  border-top: 1px solid #f1f1f1;
}
</style>