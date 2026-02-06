<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Patient $patient
 */
$this->assign('title', 'Editează pacient');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-edit"></i>
                Editează pacient
            </h1>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la pacient',
                ['action' => 'view', $patient->id],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>

        <div class="card">
            <div class="card-body">
                <?= $this->Form->create($patient, ['class' => 'needs-validation', 'novalidate' => true]) ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('full_name', [
                                'type' => 'text',
                                'label' => ['text' => 'Nume complet', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'maxlength' => 100,
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('phone', [
                                'type' => 'text',
                                'label' => ['text' => 'Telefon', 'class' => 'form-label'],
                                'class' => 'form-control',
                                'required' => true,
                                'placeholder' => '07xxxxxxxx',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <?= $this->Form->control('orizont_extins_programare', [
                                'type' => 'checkbox',
                                'label' => 'Orizont extins de programare (90 zile)',
                                'class' => 'form-check-input',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informații cont (doar citire):</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Email:</strong> <?= h($patient->email) ?></li>
                            <li><strong>Data înregistrării:</strong> <?= h($patient->created->format('d F Y, H:i')) ?></li>
                        </ul>
                        <small class="text-muted mt-1 d-block">Adresa de email și parola pot fi modificate doar de pacient prin portalul dedicat.</small>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Actualizează pacient',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escapeTitle' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Anulează',
                        ['action' => 'view', $patient->id],
                        ['class' => 'btn btn-secondary']
                    ) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
