<?php

namespace Viviniko\Menu\Contracts;

interface Factory
{
    /**
     * Build menu by name.
     *
     * @param $name
     * @return mixed
     */
    public function make($name);

    /**
     * @param $name
     * @return mixed
     */
    public function has($name);

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