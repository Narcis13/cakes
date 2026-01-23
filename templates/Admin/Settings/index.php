<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Setting> $settings
 */
?>
<div class="settings index content">
    <div class="float-end mb-2">
        <?= $this->Html->link(__('Setări Email'), ['action' => 'email'], ['class' => 'btn btn-success me-2']) ?>
        <?= $this->Html->link(__('Setare nouă'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </div>
    <h3><?= __('Setări') ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th><?= $this->Paginator->sort('key_name', 'Cheie') ?></th>
                    <th><?= $this->Paginator->sort('value', 'Valoare') ?></th>
                    <th><?= $this->Paginator->sort('description', 'Descriere') ?></th>
                    <th><?= $this->Paginator->sort('type', 'Tip') ?></th>
                    <th><?= $this->Paginator->sort('modified', 'Modificat') ?></th>
                    <th class="actions"><?= __('Acțiuni') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settings as $setting): ?>
                <tr>
                    <td><?= $this->Number->format($setting->id) ?></td>
                    <td><?= h($setting->key_name) ?></td>
                    <td><?= h(mb_strimwidth((string)$setting->value, 0, 50, '...')) ?></td>
                    <td><?= h($setting->description) ?></td>
                    <td><?= h($setting->type) ?></td>
                    <td><?= h($setting->modified->format('d.m.Y H:i:s')) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Vizualizează'), ['action' => 'view', $setting->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editează'), ['action' => 'edit', $setting->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Șterge'), ['action' => 'delete', $setting->id], ['confirm' => __('Ești sigur că vrei să ștergi setarea {0}?', $setting->key_name), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('prima')) ?>
            <?= $this->Paginator->prev('< ' . __('anterioara')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('următoarea') . ' >') ?>
            <?= $this->Paginator->last(__('ultima') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrare/înregistrări din {{count}} total')) ?></p>
    </div>
</div>
