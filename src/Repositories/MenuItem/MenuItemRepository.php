<?php

namespace Viviniko\Menu\Repositories\MenuItem;

use Viviniko\Menu\Models\Menu;

interface MenuItemRepository
{

    /**
     * Get menu items tree by menu.
     *
     * @param $menuId
     * @return mixed
     */
	public function getTreeByMenuId($menuId);

	/**
	 * Find system Menu by id.
	 *
	 * @param $id Menu Id
	 * @return Menu|null
	 */
	public function find($id);

	/**
	 * Create new system menu.
	 *
	 * @param array $data
	 * @return Menu
	 */
	public function create(array $data);

	/**
	 * Update specified menu.
	 *
	 * @param $id Menu Id
	 * @param array $data
	 * @return Menu
	 */
	public function update($id, array $data);

	/**
	 * Remove menu item from repository.
	 *
	 * @param $id Menu Id
	 * @return bool
	 */
	public function delete($id);

    /**
     * Remove menu items from repository.
     *
     * @param array | string $ids
     * @return int
     */
	public function deleteAll($ids);

}