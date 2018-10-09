<?php

namespace Viviniko\Menu\Models;

use Viviniko\Permission\Models\Permission;
use Viviniko\Support\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $tableConfigKey = 'menu.menu_items_table';

    protected $fillable = [
        'menu_id', 'title', 'description', 'url', 'target', 'icon_class', 'color', 'parent_id', 'permission_id', 'sort'
    ];

    public function getTextAttribute()
    {
        $text = $this->title;
        if (!empty($this->url)) {
            $text .= " ($this->url)";
        }
        return $text;
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function allow($user)
    {
        if (!empty($this->permission) && $user) {
            return $user->can($this->permission->name);
        }
        if (count($this->children) > 0) {
            foreach($this->children as $item) {
                if ($item->allow($user)) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

}