<?php

namespace Viviniko\Menu\Contracts;

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
}