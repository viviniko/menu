<?php

namespace Viviniko\Menu\Services;

use Viviniko\Menu\Repositories\MenuItem\MenuItemRepository;
use Viviniko\Menu\Repositories\Menu\MenuRepository;

class MenuServiceImpl implements MenuService
{
    protected $menus;

    protected $menuItems;

    public function __construct(MenuRepository $menus, MenuItemRepository $menuItems)
    {
        $this->menus = $menus;
        $this->menuItems = $menuItems;
    }

    public function menus()
    {
        return $this->menus->all();
    }

    public function getMenu($id)
    {
        return $this->menus->find($id);
    }

    public function createMenu(array $data)
    {
        return $this->menus->create($data);
    }

    public function updateMenu($id, array $data)
    {
        return $this->menus->update($id, $data);
    }

    public function deleteMenu($id)
    {
        return $this->menus->delete($id);
    }

    public function getMenuItemsByMenuId($menuId)
    {
        return $this->menuItems->findByMenuId($menuId);
    }

    public function getMenuItem($id)
    {
        return $this->menuItems->find($id);
    }

    public function createMenuItem(array $data)
    {
        return $this->menuItems->create($data);
    }

    public function updateMenuItem($id, array $data)
    {
        return $this->menuItems->update($id, $data);
    }

    public function deleteMenuItem($id)
    {
        return $this->menuItems->delete($id);
    }
}