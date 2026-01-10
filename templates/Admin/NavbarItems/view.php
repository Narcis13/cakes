<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NavbarItem $navbarItem
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Acțiuni') ?></h4>
            <?= $this->Html->link(__('Editează element meniu'), ['action' => 'edit', $navbarItem->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Șterge element meniu'), ['action' => 'delete', $navbarItem->id], ['confirm' => __('Sigur doriți să ștergeți # {0}?', $navbarItem->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Listă elemente meniu'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Element meniu nou'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="navbarItems view content">
            <h3><?= h($navbarItem->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Element meniu părinte') ?></th>
                    <td><?= $navbarItem->hasValue('parent_navbar_item') ? $this->Html->link($navbarItem->parent_navbar_item->title, ['controller' => 'NavbarItems', 'action' => 'view', $navbarItem->parent_navbar_item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Titlu') ?></th>
                    <td><?= h($navbarItem->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('URL') ?></th>
                    <td><?= h($navbarItem->url) ?></td>
                </tr>
                <tr>
                    <th><?= __('Destinație') ?></th>
                    <td><?= h($navbarItem->target) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pictogramă') ?></th>
                    <td><?= h($navbarItem->icon) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($navbarItem->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ordine sortare') ?></th>
                    <td><?= $this->Number->format($navbarItem->sort_order) ?></td>
                </tr>
                <tr>
                    <th><?= __('Creat') ?></th>
                    <td><?= h($navbarItem->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modificat') ?></th>
                    <td><?= h($navbarItem->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Este activ') ?></th>
                    <td><?= $navbarItem->is_active ? __('Da') : __('Nu'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Elemente meniu asociate') ?></h4>
                <?php if (!empty($navbarItem->child_navbar_items)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Id părinte') ?></th>
                            <th><?= __('Titlu') ?></th>
                            <th><?= __('URL') ?></th>
                            <th><?= __('Destinație') ?></th>
                            <th><?= __('Pictogramă') ?></th>
                            <th><?= __('Ordine sortare') ?></th>
                            <th><?= __('Este activ') ?></th>
                            <th><?= __('Creat') ?></th>
                            <th><?= __('Modificat') ?></th>
                            <th class="actions"><?= __('Acțiuni') ?></th>
                        </tr>
                        <?php foreach ($navbarItem->child_navbar_items as $childNavbarItem) : ?>
                        <tr>
                            <td><?= h($childNavbarItem->id) ?></td>
                            <td><?= h($childNavbarItem->parent_id) ?></td>
                            <td><?= h($childNavbarItem->title) ?></td>
                            <td><?= h($childNavbarItem->url) ?></td>
                            <td><?= h($childNavbarItem->target) ?></td>
                            <td><?= h($childNavbarItem->icon) ?></td>
                            <td><?= h($childNavbarItem->sort_order) ?></td>
                            <td><?= h($childNavbarItem->is_active) ?></td>
                            <td><?= h($childNavbarItem->created) ?></td>
                            <td><?= h($childNavbarItem->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('Vizualizează'), ['controller' => 'NavbarItems', 'action' => 'view', $childNavbarItem->id]) ?>
                                <?= $this->Html->link(__('Editează'), ['controller' => 'NavbarItems', 'action' => 'edit', $childNavbarItem->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Șterge'),
                                    ['controller' => 'NavbarItems', 'action' => 'delete', $childNavbarItem->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Sigur doriți să ștergeți # {0}?', $childNavbarItem->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
