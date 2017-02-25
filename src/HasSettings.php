<?php

namespace Yarob\LaravelModelSettings;

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
     * Return the sluggable configuration array for this model.
     * Must be implemented at the model class
     *
     * @return array
     */
    public function settings()
    {
    	return new ModelSettingsService($this);
    }
}
