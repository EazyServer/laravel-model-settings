<?php

namespace Yarob\LaravelModelSettings;

use Illuminate\Support\ServiceProvider as Provider;
use Yarob\LaravelModelSettings\Services\ModelSettingsService;

class ServiceProvider extends Provider
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
		$this->publishes([
			                 __DIR__ . '/../resources/config/model-settings.php' => config_path('model-settings.php'),
		                 ], 'config');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../resources/config/model-settings.php', 'model-settings');

		$this->app->singleton(HasSettingsObserver::class, function ($app) {
			return new HasSettingsObserver(new ModelSettingsService(), $app['events']);
		});
	}
}