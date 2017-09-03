<?php

namespace Viviniko\Menu\Services\Menu;

use Illuminate\Support\Facades\Auth;
use Viviniko\Menu\Contracts\MenuService as MenuServiceInterface;
use Viviniko\Menu\Repositories\MenuItem\MenuItemRepository;
use Viviniko\Menu\Repositories\Menu\MenuRepository;

class MenuServiceImpl implements MenuServiceInterface
{
    protected $menus;

    protected $menuItems;

    protected $builtMenus;

    public function __construct(MenuRepository $menus, MenuItemRepository $menuItems)
    {
        $this->menus = $menus;
        $this->menuItems = $menuItems;
        $this->builtMenus = collect([]);
    }

    public function has($key)
    {
        return $this->menus->exists('name', $key);
    }

    public function build($name)
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

    protected function buildMenuItems($menu, $items) {
        $user = Auth::user();
        foreach ($items as $item) {
            if ($item->allowed($user)) {
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