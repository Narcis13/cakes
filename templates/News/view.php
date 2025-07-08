<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $news
 */
?>
<?php $this->assign('title', h($news->title)); ?>

<!-- ======= News Article Section ======= -->
<section id="news-article" class="news-article">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="article">
                    <!-- Article Header -->
                    <div class="article-header mb-4">
                        <?php if ($news->news_category): ?>
                            <span class="article-category badge bg-primary mb-3">
                                <?= h($news->news_category->name) ?>
                            </span>
                        <?php endif; ?>
                        
                        <h1 class="article-title"><?= h($news->title) ?></h1>
                        
                        <div class="article-meta">
                            <span class="article-date">
                                <i class="bi bi-calendar"></i>
                                <?= h($news->publish_date ? $news->publish_date->format('F d, Y') : $news->created->format('F d, Y')) ?>
                            </span>
                            <?php if ($news->staff): ?>
                                <span class="article-author ms-3">
                                    <i class="bi bi-person"></i>
                                    Dr. <?= h($news->staff->name) ?>
                                </span>
                            <?php endif; ?>
                            <span class="article-views ms-3">
                                <i class="bi bi-eye"></i>
                                <?= $this->Number->format($news->views_count) ?> views
                            </span>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <?php if ($news->featured_image): ?>
                        <div class="article-image mb-4">
                            <?= $this->Html->image('news/' . $news->featured_image, [
                                'alt' => h($news->title),
                                'class' => 'img-fluid rounded'
                            ]) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="article-content">
                        <?php if ($news->excerpt): ?>
                            <div class="article-excerpt lead mb-4">
                                <?= h($news->excerpt) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="article-body">
                            <?= $news->content ?>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="article-share mt-5">
                        <h5>Share this article:</h5>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($this->Url->build(['action' => 'view', $news->slug], ['fullBase' => true])) ?>" 
                               target="_blank" 
                               class="btn btn-facebook">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($this->Url->build(['action' => 'view', $news->slug], ['fullBase' => true])) ?>&text=<?= urlencode($news->title) ?>" 
                               target="_blank" 
                               class="btn btn-twitter">
                                <i class="bi bi-twitter"></i> Twitter
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($this->Url->build(['action' => 'view', $news->slug], ['fullBase' => true])) ?>&title=<?= urlencode($news->title) ?>" 
                               target="_blank" 
                               class="btn btn-linkedin">
                                <i class="bi bi-linkedin"></i> LinkedIn
                            </a>
                            <a href="mailto:?subject=<?= urlencode($news->title) ?>&body=<?= urlencode('Check out this article: ' . $this->Url->build(['action' => 'view', $news->slug], ['fullBase' => true])) ?>" 
                               class="btn btn-email">
                                <i class="bi bi-envelope"></i> Email
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Back to News -->
                    <div class="sidebar-item mb-4">
                        <?= $this->Html->link(
                            '<i class="bi bi-arrow-left"></i> Back to All News',
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-primary w-100', 'escape' => false]
                        ) ?>
                    </div>

                    <!-- Author Info -->
                    <?php if ($news->staff): ?>
                        <div class="sidebar-item author-info">
                            <h4 class="sidebar-title">About the Author</h4>
                            <div class="author-card">
                                <?php if ($news->staff->photo): ?>
                                    <div class="author-photo mb-3">
                                        <?= $this->Html->image('staff/' . $news->staff->photo, [
                                            'alt' => h($news->staff->name),
                                            'class' => 'rounded-circle',
                                            'width' => 100,
                                            'height' => 100
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                                <h5>Dr. <?= h($news->staff->name) ?></h5>
                                <?php if ($news->staff->specialization): ?>
                                    <p class="text-muted"><?= h($news->staff->specialization) ?></p>
                                <?php endif; ?>
                                <?php if ($news->staff->department): ?>
                                    <p class="small">
                                        <i class="bi bi-building"></i> 
                                        <?= h($news->staff->department->name) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Quick Contact -->
                    <div class="sidebar-item contact-info">
                        <h4 class="sidebar-title">Need More Information?</h4>
                        <div class="contact-card">
                            <p>For more information about this news or our services, please contact us:</p>
                            <p class="mb-2">
                                <i class="bi bi-telephone"></i>
                                <strong>Phone:</strong> +1 5589 55488 55
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-envelope"></i>
                                <strong>Email:</strong> info@hospital.com
                            </p>
                            <?= $this->Html->link(
                                'Contact Us',
                                ['controller' => 'Pages', 'action' => 'contact'],
                                ['class' => 'btn btn-primary btn-sm mt-3']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- End News Article Section -->

<style>
.news-article {
    padding: 60px 0;
}

.article {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.article-title {
    font-size: 2.5rem;
    color: #2c4964;
    margin-bottom: 20px;
    line-height: 1.2;
}

.article-meta {
    font-size: 14px;
    color: #777;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.article-meta i {
    margin-right: 5px;
    color: #1977cc;
}

.article-category {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.article-image img {
    width: 100%;
    height: auto;
}

.article-excerpt {
    font-size: 1.2rem;
    color: #555;
    font-style: italic;
}

.article-body {
    font-size: 16px;
    line-height: 1.8;
    color: #444;
}

.article-body h2, 
.article-body h3, 
.article-body h4 {
    margin-top: 30px;
    margin-bottom: 15px;
    color: #2c4964;
}

.article-body p {
    margin-bottom: 20px;
}

.article-body ul, 
.article-body ol {
    margin-bottom: 20px;
    padding-left: 30px;
}

.article-body img {
    max-width: 100%;
    height: auto;
    margin: 20px 0;
}

/* Share Buttons */
.article-share {
    padding-top: 30px;
    border-top: 1px solid #eee;
}

.share-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.share-buttons .btn {
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: opacity 0.3s;
}

.share-buttons .btn:hover {
    opacity: 0.8;
}

.btn-facebook {
    background-color: #1877f2;
}

.btn-twitter {
    background-color: #1da1f2;
}

.btn-linkedin {
    background-color: #0077b5;
}

.btn-email {
    background-color: #6c757d;
}

/* Sidebar */
.sidebar {
    margin-top: 40px;
}

.sidebar-item {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.sidebar-title {
    font-size: 1.3rem;
    color: #2c4964;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #1977cc;
}

.author-card {
    text-align: center;
}

.author-photo img {
    border: 4px solid #1977cc;
}

.contact-card {
    font-size: 14px;
}

.contact-card i {
    color: #1977cc;
    margin-right: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .article {
        padding: 20px;
    }
    
    .article-title {
        font-size: 1.8rem;
    }
    
    .sidebar {
        margin-top: 20px;
    }
    
    .share-buttons {
        justify-content: center;
    }
}
</style>