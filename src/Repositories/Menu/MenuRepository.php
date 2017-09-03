<?php

namespace Viviniko\Menu\Repositories\Menu;

use Viviniko\Menu\Models\Menu;

interface MenuRepository
{
	/**
	 * Get all system menus.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function all();

	/**
	 * Find system menus by id.
	 *
	 * @param $id menu Id
	 * @return Menu|null
	 */
	public function find($id);

	/**
	 * Find menu by name:
	 *
	 * @param $name
	 * @return mixed
	 */
	public function findByName($name);

    /**
     * Entity exists.
     *
     * @param $column
     * @param $value
     * @return bool
     */
	public function exists($column, $value = null);

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
	 * @param $id menu Id
	 * @param array $data
	 * @return Menu
	 */
	public function update($id, array $data);

	/**
	 * Remove menu from repository.
	 *
	 * @param $id menu Id
	 * @return bool
	 */
	public function delete($id);

}