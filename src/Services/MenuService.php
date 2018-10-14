<?php

namespace Viviniko\Menu\Services;

interface MenuService
{
    public function menus();

    public function getMenu($id);

    public function createMenu(array $data);

    public function updateMenu($id, array $data);

    public function deleteMenu($id);

    public function getMenuItemsByMenuId($menuId);

    public function getMenuItem($id);

    public function createMenuItem(array $data);

    public function updateMenuItem($id, array $data);

    public function deleteMenuItem($id);
}