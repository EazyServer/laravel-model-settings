<?php

namespace Yarob\LaravelModelSettings;

use ErrorException;
use Yarob\LaravelModelSettings\Services\ModelSettingsService;

/**
 * Class HasSettings
 *
 * @package Yarob\HasSettings
 */
trait HasSettings
{

    /**
     * Hook into the Eloquent model events to create or
     * update the settings as required.
     */
    public static function bootHasSettings()
    {
        static::observe(app(HasSettingsObserver::class));
    }

    /**
     * Register a saving-settings model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function savingSettings($callback)
    {
        static::registerModelEvent('saving-settings', $callback);
    }

    /**
     * Register a settings-saved model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function settingsSaved($callback)
    {
        static::registerModelEvent('settings-saved', $callback);
    }

    /**
     *
     * @return \Yarob\LaravelModelSettings\Services\ModelSettingsService
     */
    public function settings()
    {
    	return new ModelSettingsService($this);
    }

	/**
	 * Define `settings` Accessor for Models
	 *
	 * @param $settings
	 * @return collection
	 * @throws ErrorException
	 */
    public function getSettingsAttribute($settings)
    {
    	$jsonDecodedSettings = json_decode($settings);

	    if(json_last_error() == JSON_ERROR_NONE)
	    {
    	    return collect( $jsonDecodedSettings );
	    }
	    else
	    {
	    	throw new ErrorException('None json format encountered in `settings` column on `'.$this->getTable().'` table on database!');
	    }
    }
}
