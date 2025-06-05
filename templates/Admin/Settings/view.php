<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
?>
<div class="row">
    <aside class="col-md-3">
        <div class="list-group">
            <strong class="list-group-item list-group-item-action active" aria-current="true"><?= __('Actions') ?></strong>
            <?= $this->Html->link(__('Edit Setting'), ['action' => 'edit', $setting->id], ['class' => 'list-group-item list-group-item-action']) ?>
            <?= $this->Form->postLink(__('Delete Setting'), ['action' => 'delete', $setting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setting->key_name), 'class' => 'list-group-item list-group-item-action text-danger']) ?>
            <?= $this->Html->link(__('List Settings'), ['action' => 'index'], ['class' => 'list-group-item list-group-item-action']) ?>
            <?= $this->Html->link(__('New Setting'), ['action' => 'add'], ['class' => 'list-group-item list-group-item-action']) ?>
        </div>
    </aside>
    <div class="col-md-9 settings view content">
        <h3><?= h($setting->key_name) ?></h3>
        <table class="table">
            <tr>
                <th><?= __('Key Name') ?></th>
                <td><?= h($setting->key_name) ?></td>
            </tr>
            <tr>
                <th><?= __('Description') ?></th>
                <td><?= h($setting->description) ?></td>
            </tr>
            <tr>
                <th><?= __('Type') ?></th>
                <td><?= h($setting->type) ?></td>
            </tr>
            <tr>
                <th><?= __('Id') ?></th>
                <td><?= $this->Number->format($setting->id) ?></td>
            </tr>
            <tr>
                <th><?= __('Created') ?></th>
                <td><?= h($setting->created->format('Y-m-d H:i:s')) ?></td>
            </tr>
            <tr>
                <th><?= __('Modified') ?></th>
                <td><?= h($setting->modified->format('Y-m-d H:i:s')) ?></td>
            </tr>
        </table>
        <div class="text">
            <strong><?= __('Value') ?></strong>
            <blockquote>
                <?= $this->Text->autoParagraph(h($setting->value)); ?>
            </blockquote>
        </div>
    </div>
</div>