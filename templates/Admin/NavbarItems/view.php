<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NavbarItem $navbarItem
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Navbar Item'), ['action' => 'edit', $navbarItem->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Navbar Item'), ['action' => 'delete', $navbarItem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $navbarItem->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Navbar Items'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Navbar Item'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="navbarItems view content">
            <h3><?= h($navbarItem->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Parent Navbar Item') ?></th>
                    <td><?= $navbarItem->hasValue('parent_navbar_item') ? $this->Html->link($navbarItem->parent_navbar_item->title, ['controller' => 'NavbarItems', 'action' => 'view', $navbarItem->parent_navbar_item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($navbarItem->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Url') ?></th>
                    <td><?= h($navbarItem->url) ?></td>
                </tr>
                <tr>
                    <th><?= __('Target') ?></th>
                    <td><?= h($navbarItem->target) ?></td>
                </tr>
                <tr>
                    <th><?= __('Icon') ?></th>
                    <td><?= h($navbarItem->icon) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($navbarItem->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sort Order') ?></th>
                    <td><?= $this->Number->format($navbarItem->sort_order) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($navbarItem->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($navbarItem->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Active') ?></th>
                    <td><?= $navbarItem->is_active ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Navbar Items') ?></h4>
                <?php if (!empty($navbarItem->child_navbar_items)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Parent Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Url') ?></th>
                            <th><?= __('Target') ?></th>
                            <th><?= __('Icon') ?></th>
                            <th><?= __('Sort Order') ?></th>
                            <th><?= __('Is Active') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
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
                                <?= $this->Html->link(__('View'), ['controller' => 'NavbarItems', 'action' => 'view', $childNavbarItem->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'NavbarItems', 'action' => 'edit', $childNavbarItem->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'NavbarItems', 'action' => 'delete', $childNavbarItem->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $childNavbarItem->id),
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