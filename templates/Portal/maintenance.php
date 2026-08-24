<?php
/**
 * Patient portal maintenance notice
 *
 * Rendered instead of any portal / online booking page while maintenance mode
 * is active (Admin > Setări > Mentenanță portal).
 *
 * @var \App\View\AppView $this
 * @var string $maintenanceTitle
 * @var string $maintenanceMessage
 * @var string $maintenanceEta
 */
$this->assign('title', 'Mentenanță portal programări');
?>
<section class="inner-page" style="padding: 60px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-tools" style="font-size: 4rem; color: #1977cc;"></i>
                        </div>

                        <h2 class="mb-3"><?= h($maintenanceTitle) ?></h2>

                        <p class="lead text-muted mb-4">
                            <?= nl2br(h($maintenanceMessage)) ?>
                        </p>

                        <?php if ($maintenanceEta !== '') : ?>
                            <div class="alert alert-info d-inline-block" role="status">
                                <i class="bi bi-clock"></i>
                                <strong>Estimare revenire:</strong> <?= h($maintenanceEta) ?>
                            </div>
                        <?php endif; ?>

                        <hr class="my-4">

                        <p class="mb-4">
                            Pentru programări urgente ne puteți contacta telefonic sau prin
                            formularul de contact.
                        </p>

                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <?= $this->Html->link(
                                '<i class="bi bi-house"></i> Pagina principală',
                                '/',
                                ['class' => 'btn btn-outline-primary', 'escape' => false],
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="bi bi-envelope"></i> Contact',
                                '/formular-contact',
                                ['class' => 'btn btn-primary', 'escape' => false],
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
