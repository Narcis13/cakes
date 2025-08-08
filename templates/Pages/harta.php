<?php
/**
 * @var \App\View\AppView $this
 */
?>

<?php $this->assign('title', 'Cum Ajungeti'); ?>

<!-- Page Title -->
<section class="page-title bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Cum Ajungeti</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><?= $this->Html->link('Acasă', '/') ?></li>
                        <li class="breadcrumb-item active" aria-current="page">Cum Ajungeti</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section with Map -->
<?= $this->cell('Contact') ?>