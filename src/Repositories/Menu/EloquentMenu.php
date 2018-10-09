<?php

namespace Viviniko\Menu\Repositories\Menu;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\EloquentRepository;

class EloquentMenu extends EloquentRepository implements MenuRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('menu.menu'));
    }

    /**
	 * {@inheritdoc}
	 */
	public function findByName($name)
    {
		return $this->findBy('name', $name)->first();
	}
}