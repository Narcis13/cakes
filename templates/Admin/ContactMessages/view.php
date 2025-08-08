<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactMessage $contactMessage
 */
?>
<?php $this->assign('title', 'Detalii Mesaj Contact'); ?>

<div class="contact-message view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Detalii Mesaj Contact') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la lista',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary me-2', 'escape' => false]
            ) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-trash"></i> Șterge',
                ['action' => 'delete', $contactMessage->id],
                [
                    'class' => 'btn btn-danger',
                    'escape' => false,
                    'confirm' => 'Ești sigur că vrei să ștergi acest mesaj?'
                ]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-envelope me-2"></i>Mesajul
                    </h5>
                </div>
                <div class="card-body">
                    <div class="message-content">
                        <?= nl2br(h($contactMessage->mesaj)) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informații Contact
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Nume și Prenume:</strong></div>
                        <div class="col-sm-7"><?= h($contactMessage->nume_prenume) ?></div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Email:</strong></div>
                        <div class="col-sm-7">
                            <a href="mailto:<?= h($contactMessage->email) ?>" class="text-decoration-none">
                                <?= h($contactMessage->email) ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Data trimiterii:</strong></div>
                        <div class="col-sm-7">
                            <?= h($contactMessage->created->format('d.m.Y H:i')) ?>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid">
                        <a href="mailto:<?= h($contactMessage->email) ?>?subject=Re: Mesajul dumneavoastră&body=Bună ziua <?= h($contactMessage->nume_prenume) ?>,%0D%0A%0D%0AVă mulțumim pentru mesajul dumneavoastră.%0D%0A%0D%0A" 
                           class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i>Răspunde prin Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-content {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.375rem;
    border-left: 4px solid #0d6efd;
    min-height: 200px;
    font-size: 1.1rem;
    line-height: 1.6;
}
</style>