<?php namespace Franzose\UloginLaravel;

use Illuminate\Support\ServiceProvider;

class UloginLaravelServiceProvider extends ServiceProvider {

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
		$this->package('franzose/ulogin-laravel', null, __DIR__);
        $this->requirePackageFiles();
        $this->bindUserModel();
	}

    /**
     * Require some useful package files.
     *
     * @return void
     */
    protected function requirePackageFiles()
    {
        require __DIR__ . '/filters.php';
        require __DIR__ . '/routes.php';
        require __DIR__ . '/macros.php';
    }

    /**
     * Bind abstract user interface to user model.
     *
     * @return void
     */
    protected function bindUserModel()
    {
        $model = $this->app['config']->get('ulogin-laravel::user_model');

        $this->app->bind('Illuminate\Auth\UserInterface', $model);
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        //
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
