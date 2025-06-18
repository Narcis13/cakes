<?php
namespace App\View\Helper;

use Cake\View\Helper;

class MenuHelper extends Helper
{
    public array $helpers = ['Html'];

    public function renderMenu($menuItems)
    {
        $out = '';
        foreach ($menuItems as $menuItem) {
            $hasChildren = !empty($menuItem->children);
            $options = [];
            $linkOptions = ['escape' => false, 'class' => 'nav-link scrollto'];

            if ($hasChildren) {
                $out .= '<li class="dropdown">';
                $out .= $this->Html->link(
                    '<span>' . $menuItem->title . '</span> <i class="bi bi-chevron-down"></i>',
                    $menuItem->url ?? '#',
                    $linkOptions
                );
                $out .= $this->renderSubMenu($menuItem->children);
            } else {
                $out .= '<li>';
                $out .= $this->Html->link($menuItem->title, $menuItem->url, $linkOptions);
            }
            $out .= '</li>';
        }
        return $out;
    }

    private function renderSubMenu($children)
    {
        $out = '<ul>';
        foreach ($children as $child) {
            $hasChildren = !empty($child->children);
            if ($hasChildren) {
                $out .= '<li class="dropdown">';
                $out .= $this->Html->link(
                    '<span>' . $child->title . '</span> <i class="bi bi-chevron-right"></i>',
                    $child->url ?? '#',
                    ['escape' => false]
                );
                $out .= $this->renderSubMenu($child->children);
            } else {
                $out .= '<li>';
                $out .= $this->Html->link($child->title, $child->url);
            }
            $out .= '</li>';
        }
        $out .= '</ul>';
        return $out;
    }
}
