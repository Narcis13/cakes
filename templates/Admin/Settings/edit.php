<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
$settingTypes = [
    'text' => 'Câmp text',
    'textarea' => 'Zonă text',
    'boolean' => 'Boolean (Bifă)',
    'number' => 'Număr',
    'email' => 'Email',
];

// Determină tipul de input pentru câmpul 'value' bazat pe tipul setării
$valueInputType = 'textarea'; // Implicit
$valueInputOptions = ['rows' => 5, 'label' => 'Valoare'];

if (!empty($setting->type)) {
    switch ($setting->type) {
        case 'text':
            $valueInputType = 'text';
            $valueInputOptions = ['label' => 'Valoare'];
            break;
        case 'boolean':
            $valueInputType = 'checkbox';
            $valueInputOptions = ['label' => 'Valoare (Este activat?)'];
            break;
        case 'number':
            $valueInputType = 'number';
            $valueInputOptions = ['label' => 'Valoare'];
            break;
        case 'email':
            $valueInputType = 'email';
            $valueInputOptions = ['label' => 'Valoare'];
            break;
    }
}
?>
<div class="row">
    <aside class="col-md-3">
        <div class="list-group">
            <strong class="list-group-item list-group-item-action active" aria-current="true"><?= __('Acțiuni') ?></strong>
            <?= $this->Form->postLink(
                __('Șterge'),
                ['action' => 'delete', $setting->id],
                ['confirm' => __('Ești sigur că vrei să ștergi setarea {0}?', $setting->key_name), 'class' => 'list-group-item list-group-item-action text-danger']
            ) ?>
            <?= $this->Html->link(__('Listă setări'), ['action' => 'index'], ['class' => 'list-group-item list-group-item-action']) ?>
        </div>
    </aside>
    <div class="col-md-9 settings form content">
        <?= $this->Form->create($setting) ?>
        <fieldset>
            <legend><?= __('Editează setare: ') . h($setting->key_name) ?></legend>
            <?php
                echo $this->Form->control('key_name', ['readonly' => true, 'help' => 'Cheia nu poate fi modificată.', 'label' => 'Cheie']);
                echo $this->Form->control('type', ['options' => $settingTypes, 'empty' => 'Selectează tipul', 'label' => 'Tip']);
                echo $this->Form->control('description', ['type' => 'textarea', 'rows' => 3, 'label' => 'Descriere']);
                echo $this->Form->control('value', ['type' => $valueInputType] + $valueInputOptions);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Trimite'), ['class' => 'btn btn-success mt-3']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
