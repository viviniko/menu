<?php

namespace Viviniko\Menu\Repositories\MenuItem;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\EloquentRepository;

class EloquentMenuItem extends EloquentRepository implements MenuItemRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('menu.menu_item'));
    }

    /**
     * Find menu items by menu.
     *
     * @param $menuId
     * @return mixed
     */
    public function getTreeByMenuId($menuId)
    {
        $query = $this->createQuery()->select('*', 'title as text', 'icon_class as icon');
        $menuItems = $query->where(['menu_id' => $menuId])->orderBy('sort', 'asc')->get();

        return build_tree($menuItems);
    }

    /**
     * Remove menu items from repository.
     *
     * @param array | string $ids
     * @return int
     */
    public function deleteAll($ids)
    {
        return $this->createModel()->destroy($ids);
    }
}