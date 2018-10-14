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

    public function findByMenuId($menuId)
    {
        return $this->findAllBy('menu_id', $menuId);
    }
}