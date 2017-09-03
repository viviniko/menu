<?php

namespace Viviniko\Menu\Repositories\MenuItem;

use Viviniko\Repository\SimpleRepository;

class EloquentMenuItem extends SimpleRepository implements MenuItemRepository
{
    /**
     * @var string
     */
    protected $modelConfigKey = 'menu.menu_item';

    /**
     * Find menu items by menu.
     *
     * @param $menuId
     * @return mixed
     */
    public function getTreeByMenuId($menuId)
    {
        $query = $this->createModel()->newQuery()->select('*', 'title as text', 'icon_class as icon');
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