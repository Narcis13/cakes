<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ScheduleException> $scheduleExceptions
 * @var array $staff
 */
?>
<div class="scheduleExceptions index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Excepții Program Medici') ?></h3>
        <div>
            <?= $this->Html->link(__('Adaugă Excepție'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Adaugă în Masă'), ['action' => 'bulkAdd'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Filtre</h5>
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3']) ?>
            
            <div class="col-md-3">
                <?= $this->Form->control('staff_id', [
                    'label' => 'Medic',
                    'options' => ['' => 'Toți medicii'] + $staff,
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('staff_id')
                ]) ?>
            </div>
            
            <div class="col-md-3">
                <?= $this->Form->control('date_from', [
                    'label' => 'De la data',
                    'type' => 'date',
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('date_from')
                ]) ?>
            </div>
            
            <div class="col-md-3">
                <?= $this->Form->control('date_to', [
                    'label' => 'Până la data',
                    'type' => 'date',
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('date_to')
                ]) ?>
            </div>
            
            <div class="col-md-3">
                <?= $this->Form->control('is_working', [
                    'label' => 'Tip excepție',
                    'options' => [
                        '' => 'Toate',
                        '1' => 'Zile lucrate suplimentar',
                        '0' => 'Zile libere'
                    ],
                    'class' => 'form-control',
                    'value' => $this->request->getQuery('is_working')
                ]) ?>
            </div>
            
            <div class="col-12">
                <?= $this->Form->button(__('Filtrează'), ['class' => 'btn btn-primary me-2']) ?>
                <?= $this->Html->link(__('Resetează'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('staff_id', 'Medic') ?></th>
                    <th><?= $this->Paginator->sort('exception_date', 'Data') ?></th>
                    <th>Zi săptămână</th>
                    <th><?= $this->Paginator->sort('is_working', 'Tip') ?></th>
                    <th>Orar</th>
                    <th><?= $this->Paginator->sort('reason', 'Motiv') ?></th>
                    <th class="actions"><?= __('Acțiuni') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $daysOfWeek = [
                    'Sunday' => 'Duminică',
                    'Monday' => 'Luni',
                    'Tuesday' => 'Marți',
                    'Wednesday' => 'Miercuri',
                    'Thursday' => 'Joi',
                    'Friday' => 'Vineri',
                    'Saturday' => 'Sâmbătă'
                ];
                ?>
                <?php foreach ($scheduleExceptions as $scheduleException): ?>
                <tr>
                    <td><?= $scheduleException->has('staff') ? h($scheduleException->staff->name) : '' ?></td>
                    <td><?= $scheduleException->exception_date->format('d.m.Y') ?></td>
                    <td><?= $daysOfWeek[$scheduleException->exception_date->format('l')] ?? '' ?></td>
                    <td>
                        <?php if ($scheduleException->is_working): ?>
                            <span class="badge bg-success">Zi lucrată</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Zi liberă</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($scheduleException->is_working && $scheduleException->start_time && $scheduleException->end_time): ?>
                            <?= $scheduleException->start_time->format('H:i') ?> - <?= $scheduleException->end_time->format('H:i') ?>
                        <?php elseif (!$scheduleException->is_working): ?>
                            <span class="text-muted">-</span>
                        <?php else: ?>
                            <span class="text-muted">Orar normal</span>
                        <?php endif; ?>
                    </td>
                    <td><?= h($scheduleException->reason) ?: '<span class="text-muted">-</span>' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Vizualizare'), ['action' => 'view', $scheduleException->id], ['class' => 'btn btn-sm btn-primary']) ?>
                        <?= $this->Html->link(__('Editare'), ['action' => 'edit', $scheduleException->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Ștergere'), ['action' => 'delete', $scheduleException->id], ['confirm' => __('Sigur doriți să ștergeți această excepție?'), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('Prima')) ?>
            <?= $this->Paginator->prev('< ' . __('Anterioară')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Următoarea') . ' >') ?>
            <?= $this->Paginator->last(__('Ultima') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrări din totalul de {{count}}')) ?></p>
    </div>
</div>