<?php

namespace Viviniko\Menu;

use Viviniko\Menu\Contracts\Factory;
use Viviniko\Menu\Repositories\MenuItem\MenuItemRepository;
use Viviniko\Menu\Repositories\Menu\MenuRepository;
use Viviniko\Menu\Elem\Builder;

class MenuFactory implements Factory
{
    protected $menus;

    protected $menuItems;

    protected $builtMenus;

    protected $user;

    public function __construct(MenuRepository $menus, MenuItemRepository $menuItems)
    {
        $this->menus = $menus;
        $this->menuItems = $menuItems;
        $this->builtMenus = collect([]);
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function has($key)
    {
        return $this->menus->exists('name', $key);
    }

    public function make($name)
    {
        if ($this->builtMenus->has($name)) {
            return $this->builtMenus->get($name);
        }

        $builder = null;
        if ($menu = $this->menus->findByName($name)) {
            $builder = new Builder($name);

            $builder->options(['header' => $menu->display_name]);
            $this->buildMenuItems($builder, $this->menuItems->getTreeByMenuId($menu->id));
        }
        $this->builtMenus->put($name, $builder);

        return $builder;
    }

    protected function buildMenuItems($menu, $items)
    {
        foreach ($items as $item) {
            if ($item->allow($this->getUser())) {
                $options = [ 'url' => $item->url, 'id' => $item->id ];
                if (!empty($item->color)) {
                    $options['style'] = 'color: ' . $item->color;
                }
                if (!empty($item->parent_id)) {
                    $options['parent'] = $item->parent_id;
                }
                $data = [
                    'icon' => $item->icon_class,
                    // 'sort_by' => $item->sort,
                ];
                $menu->add($item->title, $options)->data($data);
                if (count($item->children) > 0) {
                    $this->buildMenuItems($menu, $item->children);
                }
            }
        }
    }
}