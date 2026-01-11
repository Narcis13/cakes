<?php
/**
 * @var \App\View\AppView $this
 * @var array $staff
 * @var array $services
 * @var array $daysOfWeek
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Html->link(__('Lista Program Medici'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="doctorSchedules form content">
            <?= $this->Form->create(null, ['url' => ['action' => 'bulkAdd']]) ?>
            <fieldset>
                <legend><?= __('Adăugare în Masă Program Medici') ?></legend>
                <p class="text-muted"><?= __('Creați programe multiple simultan pentru mai mulți medici și zile.') ?></p>

                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('staff_ids', [
                            'label' => __('Selectați medicii'),
                            'options' => $staff,
                            'multiple' => true,
                            'class' => 'form-select',
                            'size' => 8,
                            'required' => true,
                            'help' => __('Țineți apăsat Ctrl/Cmd pentru a selecta mai mulți medici')
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('days_of_week', [
                            'label' => __('Selectați zilele'),
                            'options' => $daysOfWeek,
                            'multiple' => true,
                            'class' => 'form-select',
                            'size' => 7,
                            'required' => true,
                            'help' => __('Țineți apăsat Ctrl/Cmd pentru a selecta mai multe zile')
                        ]) ?>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('service_id', [
                            'label' => __('Serviciu'),
                            'options' => $services,
                            'empty' => __('-- Selectați serviciul --'),
                            'class' => 'form-select',
                            'required' => true
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('start_time', [
                            'label' => __('Ora început'),
                            'type' => 'time',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('end_time', [
                            'label' => __('Ora sfârșit'),
                            'type' => 'time',
                            'class' => 'form-control',
                            'required' => true
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <?= $this->Form->control('max_appointments', [
                            'label' => __('Număr maxim programări'),
                            'type' => 'number',
                            'min' => 1,
                            'default' => 1,
                            'class' => 'form-control',
                            'help' => __('Numărul maxim de programări permise în acest interval orar')
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('slot_duration', [
                            'label' => __('Durată interval (minute)'),
                            'type' => 'number',
                            'min' => 5,
                            'step' => 5,
                            'class' => 'form-control',
                            'help' => __('Lăsați gol pentru a folosi durata implicită a serviciului')
                        ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('buffer_minutes', [
                            'label' => __('Minute tampon'),
                            'type' => 'number',
                            'min' => 0,
                            'default' => 0,
                            'class' => 'form-control',
                            'help' => __('Minute între programări')
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('is_active', [
                            'label' => __('Activ'),
                            'type' => 'checkbox',
                            'checked' => true,
                            'class' => 'form-check-input'
                        ]) ?>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <strong><?= __('Notă:') ?></strong> <?= __('Aceasta va crea programe pentru toate combinațiile selectate de medici și zile cu aceleași setări de timp.') ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Creează Programe'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Anulează'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
