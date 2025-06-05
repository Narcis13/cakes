<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
$settingTypes = [
    'text' => 'Text Input',
    'textarea' => 'Text Area',
    'boolean' => 'Boolean (Checkbox)', // Will render as checkbox if DB field is boolean
    'number' => 'Number',
    'email' => 'Email',
];

// Determine input type for the 'value' field based on setting's type
$valueInputType = 'textarea'; // Default
$valueInputOptions = ['rows' => 5, 'label' => 'Value'];

if (!empty($setting->type)) {
    switch ($setting->type) {
        case 'text':
            $valueInputType = 'text';
            $valueInputOptions = ['label' => 'Value'];
            break;
        case 'boolean':
            $valueInputType = 'checkbox'; // FormHelper handles this well
            $valueInputOptions = ['label' => 'Value (Is Enabled?)'];
            break;
        case 'number':
            $valueInputType = 'number';
            $valueInputOptions = ['label' => 'Value'];
            break;
        case 'email':
            $valueInputType = 'email';
            $valueInputOptions = ['label' => 'Value'];
            break;
        // Add more cases if needed
    }
}
?>
<div class="row">
    <aside class="col-md-3">
        <div class="list-group">
            <strong class="list-group-item list-group-item-action active" aria-current="true"><?= __('Actions') ?></strong>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $setting->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $setting->key_name), 'class' => 'list-group-item list-group-item-action text-danger']
            ) ?>
            <?= $this->Html->link(__('List Settings'), ['action' => 'index'], ['class' => 'list-group-item list-group-item-action']) ?>
        </div>
    </aside>
    <div class="col-md-9 settings form content">
        <?= $this->Form->create($setting) ?>
        <fieldset>
            <legend><?= __('Edit Setting: ') . h($setting->key_name) ?></legend>
            <?php
                echo $this->Form->control('key_name', ['readonly' => true, 'help' => 'Key name cannot be changed.']); // Or disable it
                echo $this->Form->control('type', ['options' => $settingTypes, 'empty' => 'Select Type']);
                echo $this->Form->control('description', ['type' => 'textarea', 'rows' => 3]);
                echo $this->Form->control('value', ['type' => $valueInputType] + $valueInputOptions);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success mt-3']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>