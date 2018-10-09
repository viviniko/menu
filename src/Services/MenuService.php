<?php

namespace Viviniko\Menu\Services;

interface MenuService
{
    /**
     * @param $name
     * @return mixed
     */
    public function has($name);

    /**
     * Build menu by name.
     *
     * @param $name
     * @return mixed
     */
    public function build($name);

    /**
     * Set permission user.
     *
     * @param $user
     * @return static
     */
    public function setUser($user);

    /**
     * Get permission user.
     *
     * @return mixed
     */
    public function getUser();
}