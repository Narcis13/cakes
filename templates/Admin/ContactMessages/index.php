<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactMessage> $contactMessages
 */
?>
<?php $this->assign('title', 'Mesaje de Contact'); ?>

<div class="contact-messages index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Mesaje de Contact') ?></h3>
        <div class="badge bg-primary">
            Total: <?= $this->Paginator->counter() ?>
        </div>
    </div>

    <?php if ($contactMessages->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th><?= $this->Paginator->sort('created', 'Data') ?></th>
                        <th><?= $this->Paginator->sort('nume_prenume', 'Nume și Prenume') ?></th>
                        <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                        <th>Mesaj</th>
                        <th class="text-center">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactMessages as $contactMessage): ?>
                    <tr>
                        <td>
                            <?= h($contactMessage->created->format('d.m.Y H:i')) ?>
                        </td>
                        <td>
                            <strong><?= h($contactMessage->nume_prenume) ?></strong>
                        </td>
                        <td>
                            <a href="mailto:<?= h($contactMessage->email) ?>" class="text-decoration-none">
                                <?= h($contactMessage->email) ?>
                            </a>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;">
                                <?= h($contactMessage->mesaj) ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <?= $this->Html->link(
                                    '<i class="fas fa-eye"></i>',
                                    ['action' => 'view', $contactMessage->id],
                                    [
                                        'class' => 'btn btn-sm btn-outline-primary',
                                        'title' => 'Vezi detalii',
                                        'escape' => false
                                    ]
                                ) ?>
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    ['action' => 'delete', $contactMessage->id],
                                    [
                                        'class' => 'btn btn-sm btn-outline-danger',
                                        'title' => 'Șterge',
                                        'escape' => false,
                                        'confirm' => 'Ești sigur că vrei să ștergi acest mesaj?'
                                    ]
                                ) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-md-6">
                <div class="dataTables_info">
                    <?= $this->Paginator->counter() ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dataTables_paginate paging_simple_numbers float-end">
                    <?= $this->Paginator->numbers([
                        'before' => $this->Paginator->prev('« Anterior'),
                        'after' => $this->Paginator->next('Următoarea »'),
                    ]) ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <h5><i class="fas fa-info-circle me-2"></i>Niciun mesaj găsit</h5>
            <p class="mb-0">Nu există mesaje de contact în baza de date.</p>
        </div>
    <?php endif; ?>
</div>