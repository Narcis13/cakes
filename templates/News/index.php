<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\News> $news
 */
?>
<?php $this->assign('title', 'Noutăți'); ?>

<!-- ======= News Section ======= -->
<section id="news" class="news section-bg">
    <div class="container">
        <div class="section-title">
            <h2>Noutăți</h2>
            <p>Rămâneți la curent cu ultimele noutăți și anunțuri de la spitalul nostru. Găsiți informații despre servicii noi, actualizări ale personalului și evenimente.</p>
        </div>

        <div class="row">
            <?php if (count($news) > 0): ?>
                <?php foreach ($news as $article): ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mb-4" data-aos="fade-up">
                        <article class="news-item">
                            <?php if ($article->featured_image): ?>
                                <div class="news-img">
                                    <?= $this->Html->link(
                                        $this->Html->image('news/' . $article->featured_image, [
                                            'alt' => h($article->title),
                                            'class' => 'img-fluid'
                                        ]),
                                        ['action' => 'view', $article->slug],
                                        ['escape' => false]
                                    ) ?>
                                </div>
                            <?php else: ?>
                                <div class="news-img news-img-placeholder">
                                    <?= $this->Html->link(
                                        '<div class="placeholder-content"><i class="bi bi-newspaper"></i><span>Noutăți</span></div>',
                                        ['action' => 'view', $article->slug],
                                        ['escape' => false]
                                    ) ?>
                                </div>
                            <?php endif; ?>

                            <div class="news-content">
                                <?php if ($article->news_category): ?>
                                    <span class="news-category badge bg-primary mb-2">
                                        <?= h($article->news_category->name) ?>
                                    </span>
                                <?php endif; ?>

                                <h4>
                                    <?= $this->Html->link(
                                        h($article->title),
                                        ['action' => 'view', $article->slug],
                                        ['class' => 'news-title']
                                    ) ?>
                                </h4>

                                <div class="news-meta mb-3">
                                    <span class="news-date">
                                        <i class="bi bi-calendar"></i>
                                        <?= h($article->publish_date ? $article->publish_date->format('M d, Y') : $article->created->format('M d, Y')) ?>
                                    </span>
                                    <?php if ($article->staff): ?>
                                        <span class="news-author ms-3">
                                            <i class="bi bi-person"></i>
                                            Dr. <?= h($article->staff->name) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($article->excerpt): ?>
                                    <p class="news-excerpt"><?= h($article->excerpt) ?></p>
                                <?php else: ?>
                                    <p class="news-excerpt"><?= $this->Text->truncate(strip_tags($article->content), 150) ?></p>
                                <?php endif; ?>

                                <?= $this->Html->link(
                                    'Citește mai mult <i class="bi bi-arrow-right"></i>',
                                    ['action' => 'view', $article->slug],
                                    ['class' => 'read-more', 'escape' => false]
                                ) ?>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Nu există articole de noutăți disponibile momentan. Vă rugăm să reveniți mai târziu.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($this->Paginator->hasPage(2)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="News pagination">
                        <ul class="pagination justify-content-center">
                            <?= $this->Paginator->first('<i class="bi bi-chevron-double-left"></i>', ['escape' => false]) ?>
                            <?= $this->Paginator->prev('<i class="bi bi-chevron-left"></i>', ['escape' => false]) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next('<i class="bi bi-chevron-right"></i>', ['escape' => false]) ?>
                            <?= $this->Paginator->last('<i class="bi bi-chevron-double-right"></i>', ['escape' => false]) ?>
                        </ul>
                    </nav>
                    <p class="text-center text-muted">
                        <?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} articol(e) din {{count}} total')) ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section><!-- End News Section -->

<style>
.news {
    padding: 60px 0;
}

.news-item {
    background: #fff;
    box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.news-item:hover {
    transform: translateY(-10px);
    box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.15);
}

.news-img {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.news-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.news-item:hover .news-img img {
    transform: scale(1.1);
}

.news-img-placeholder {
    background: linear-gradient(135deg, #1977cc 0%, #2c4964 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.news-img-placeholder a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    text-decoration: none;
}

.news-img-placeholder .placeholder-content {
    text-align: center;
    color: #fff;
}

.news-img-placeholder .placeholder-content i {
    font-size: 4rem;
    display: block;
    margin-bottom: 10px;
    opacity: 0.9;
}

.news-img-placeholder .placeholder-content span {
    font-size: 1.1rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 2px;
    opacity: 0.9;
}

.news-content {
    padding: 30px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.news-category {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.news-title {
    color: #2c4964;
    text-decoration: none;
    transition: color 0.3s;
}

.news-title:hover {
    color: #1977cc;
}

.news-meta {
    font-size: 14px;
    color: #777;
}

.news-meta i {
    margin-right: 5px;
    color: #1977cc;
}

.news-excerpt {
    color: #444;
    margin-bottom: 20px;
    flex-grow: 1;
}

.read-more {
    font-weight: 600;
    color: #1977cc;
    text-decoration: none;
    transition: color 0.3s;
    margin-top: auto;
}

.read-more:hover {
    color: #3291e6;
}

.read-more i {
    font-size: 12px;
    margin-left: 5px;
    transition: transform 0.3s;
}

.read-more:hover i {
    transform: translateX(5px);
}

/* Pagination styling */
.pagination {
    margin-top: 30px;
}

.pagination .page-link {
    color: #1977cc;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
}

.pagination .page-link:hover {
    background-color: #1977cc;
    color: #fff;
    border-color: #1977cc;
}

.pagination .active .page-link {
    background-color: #1977cc;
    border-color: #1977cc;
}

.pagination .disabled .page-link {
    color: #6c757d;
}
</style>