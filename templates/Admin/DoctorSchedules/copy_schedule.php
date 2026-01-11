<?php
/**
 * @var \App\View\AppView $this
 * @var array $staff
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
            <?= $this->Form->create(null, ['url' => ['action' => 'copySchedule']]) ?>
            <fieldset>
                <legend><?= __('Copiază Program Medic') ?></legend>
                <p class="text-muted"><?= __('Copiați toate programele active de la un medic la alți medici.') ?></p>

                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Form->control('source_staff_id', [
                            'label' => __('Copiază de la (Medic sursă)'),
                            'options' => $staff,
                            'empty' => __('-- Selectați medicul sursă --'),
                            'class' => 'form-select',
                            'required' => true,
                            'help' => __('Selectați medicul al cărui program doriți să îl copiați')
                        ]) ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <?= $this->Form->control('target_staff_ids', [
                            'label' => __('Copiază către (Medici destinație)'),
                            'options' => $staff,
                            'multiple' => true,
                            'class' => 'form-select',
                            'size' => 8,
                            'required' => true,
                            'help' => __('Selectați unul sau mai mulți medici către care să copiați programul. Țineți apăsat Ctrl/Cmd pentru selecție multiplă.')
                        ]) ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <?= $this->Form->control('adjust_minutes', [
                            'label' => __('Ajustare timp (minute)'),
                            'type' => 'number',
                            'default' => 0,
                            'class' => 'form-control',
                            'help' => __('Ajustați toate orele cu acest număr de minute. Folosiți valori negative pentru a muta mai devreme, pozitive pentru mai târziu. Lăsați 0 pentru nicio ajustare.')
                        ]) ?>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <strong><?= __('Important:') ?></strong>
                    <ul class="mb-0">
                        <li><?= __('Doar programele active vor fi copiate.') ?></li>
                        <li><?= __('Dacă un program intră în conflict cu un program existent al medicului destinație, acesta va fi omis.') ?></li>
                        <li><?= __('Toate programele copiate vor fi setate ca active.') ?></li>
                    </ul>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Copiază Programe'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Anulează'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Disable source doctor in target list when selected
    const sourceSelect = document.getElementById('source-staff-id');
    const targetSelect = document.getElementById('target-staff-ids');

    if (sourceSelect && targetSelect) {
        sourceSelect.addEventListener('change', function() {
            const selectedValue = this.value;

            // Enable all options first
            Array.from(targetSelect.options).forEach(option => {
                option.disabled = false;
            });

            // Disable the selected source doctor
            if (selectedValue) {
                Array.from(targetSelect.options).forEach(option => {
                    if (option.value === selectedValue) {
                        option.disabled = true;
                        option.selected = false;
                    }
                });
            }
        });
    }
});
</script>
