<?php

namespace Viviniko\Menu;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Viviniko\Menu\Console\Commands\MenuTableCommand;

class MenuServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Extending Blade engine
        Blade::extend( function($view, $compiler){
            $pattern = '/(\s*)@lm-attrs\s*\((\$[^)]+)\)/';
            return preg_replace($pattern,
                '$1<?php $lm_attrs = $2->attr(); ob_start(); ?>',
                $view);
        });

        /*
        |--------------------------------------------------------------------------
        | @lm-endattrs
        |--------------------------------------------------------------------------
        |
        | Reads the buffer data using ob_get_clean()
        | and passes it to MergeStatic().
        | mergeStatic() takes the static string,
        | converts it into a normal array and merges it with others.
        |
        */
        Blade::extend( function($view, $compiler){

            $pattern = '/(?<!\w)(\s*)@lm-endattrs(\s*)/';
            return preg_replace($pattern,
                '$1<?php echo \Viviniko\Menu\Services\Menu\Builder::mergeStatic(ob_get_clean(), $lm_attrs); ?>$2',
                $view);
        });

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-menu');

        $this->publishes([
            __DIR__.'/../config/menu.php' => config_path('menu.php'),
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/viviniko-menu'),
        ]);

        // Register commands
        $this->commands('command.menu.table');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->mergeConfigFrom(__DIR__ . '/../config/menu.php', 'menu');

        $this->registerRepositories();

        $this->registerMenuService();

        $this->registerCommands();
	}

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.menu.table', function ($app) {
            return new MenuTableCommand($app['files'], $app['composer']);
        });
    }

	protected function registerRepositories()
    {
        $this->app->singleton(
            \Viviniko\Menu\Repositories\Menu\MenuRepository::class,
            \Viviniko\Menu\Repositories\Menu\EloquentMenu::class
        );

        $this->app->singleton(
            \Viviniko\Menu\Repositories\MenuItem\MenuItemRepository::class,
            \Viviniko\Menu\Repositories\MenuItem\EloquentMenuItem::class
        );
    }

    protected function registerMenuService()
    {
        $this->app->singleton(
            \Viviniko\Menu\Contracts\MenuService::class,
            \Viviniko\Menu\Services\Menu\MenuServiceImpl::class
        );

        $this->app->singleton('menu', \Viviniko\Menu\Menu::class);
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
            \Viviniko\Menu\Repositories\Menu\MenuRepository::class,
            \Viviniko\Menu\Repositories\MenuItem\MenuItemRepository::class,
            \Viviniko\Menu\Contracts\MenuService::class,
        ];
	}

}
