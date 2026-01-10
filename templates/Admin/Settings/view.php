<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
?>
<div class="row">
    <aside class="col-md-3">
        <div class="list-group">
            <strong class="list-group-item list-group-item-action active" aria-current="true"><?= __('Acțiuni') ?></strong>
            <?= $this->Html->link(__('Editează setare'), ['action' => 'edit', $setting->id], ['class' => 'list-group-item list-group-item-action']) ?>
            <?= $this->Form->postLink(__('Șterge setare'), ['action' => 'delete', $setting->id], ['confirm' => __('Ești sigur că vrei să ștergi setarea {0}?', $setting->key_name), 'class' => 'list-group-item list-group-item-action text-danger']) ?>
            <?= $this->Html->link(__('Listă setări'), ['action' => 'index'], ['class' => 'list-group-item list-group-item-action']) ?>
            <?= $this->Html->link(__('Setare nouă'), ['action' => 'add'], ['class' => 'list-group-item list-group-item-action']) ?>
        </div>
    </aside>
    <div class="col-md-9 settings view content">
        <h3><?= h($setting->key_name) ?></h3>
        <table class="table">
            <tr>
                <th><?= __('Cheie') ?></th>
                <td><?= h($setting->key_name) ?></td>
            </tr>
            <tr>
                <th><?= __('Descriere') ?></th>
                <td><?= h($setting->description) ?></td>
            </tr>
            <tr>
                <th><?= __('Tip') ?></th>
                <td><?= h($setting->type) ?></td>
            </tr>
            <tr>
                <th><?= __('ID') ?></th>
                <td><?= $this->Number->format($setting->id) ?></td>
            </tr>
            <tr>
                <th><?= __('Creat') ?></th>
                <td><?= $setting->created ? h($setting->created->format('d.m.Y H:i:s')) : '-' ?></td>
            </tr>
            <tr>
                <th><?= __('Modificat') ?></th>
                <td><?= $setting->modified ? h($setting->modified->format('d.m.Y H:i:s')) : '-' ?></td>
            </tr>
        </table>
        <div class="text">
            <strong><?= __('Valoare') ?></strong>
            <blockquote>
                <?= $this->Text->autoParagraph(h($setting->value)); ?>
            </blockquote>
        </div>
    </div>
</div>
