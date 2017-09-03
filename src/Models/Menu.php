<?php

namespace Viviniko\Menu\Models;

use Viviniko\Support\Database\Eloquent\Model;

class Menu extends Model
{
    protected $tableConfigKey = 'menu.menus_table';

    protected $fillable = ['name', 'display_name', 'description', 'sort'];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}
