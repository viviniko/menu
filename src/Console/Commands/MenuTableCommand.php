<?php

namespace Viviniko\Menu\Console\Commands;

use Viviniko\Support\Console\CreateMigrationCommand;

class MenuTableCommand extends CreateMigrationCommand
{
    /**
     * @var string
     */
    protected $name = 'menu:table';

    /**
     * @var string
     */
    protected $description = 'Create a migration for the menu service table';

    /**
     * @var string
     */
    protected $stub = __DIR__.'/stubs/menu.stub';

    /**
     * @var string
     */
    protected $migration = 'create_menu_table';
}
