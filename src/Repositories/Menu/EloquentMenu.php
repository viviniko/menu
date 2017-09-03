<?php

namespace Viviniko\Menu\Repositories\Menu;

use Viviniko\Repository\SimpleRepository;

class EloquentMenu extends SimpleRepository implements MenuRepository
{
    /**
     * @var string
     */
    protected $modelConfigKey = 'menu.menu';

	/**
	 * {@inheritdoc}
	 */
	public function all()
    {
        return $this->search([])->get();
	}

	/**
	 * {@inheritdoc}
	 */
	public function findByName($name)
    {
		return $this->findBy('name', $name)->first();
	}

}