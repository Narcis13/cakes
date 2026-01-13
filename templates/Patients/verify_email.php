<?php
/**
 * @var \App\View\AppView $this
 *
 * Note: This template is typically not rendered as the controller
 * redirects after verification. It serves as a fallback.
 */
$this->assign('title', 'Verificare email');
$this->setLayout('admin_login');
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background-color: #1976d2;">
                <h4 class="mb-0">
                    <i class="fas fa-envelope-open-text"></i>
                    Verificare email
                </h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>

                <h5 class="mb-3">Verificare în curs...</h5>

                <p class="text-muted mb-4">
                    Vă rugăm să așteptați. Veți fi redirecționat automat.
                </p>

                <hr class="my-3">

                <div class="text-center">
                    <?= $this->Html->link(
                        '<i class="fas fa-sign-in-alt"></i> Continuă către autentificare',
                        ['action' => 'login'],
                        [
                            'class' => 'btn btn-primary',
                            'style' => 'background-color: #1976d2; border-color: #1976d2;',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
