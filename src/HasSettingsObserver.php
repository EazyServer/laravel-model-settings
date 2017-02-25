<?php namespace Yarob\LaravelModelSettings;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Yarob\LaravelModelSettings\Services\ModelSettingsService;

/**
 * Class HasSettingsObserver
 *
 * @package Yarob\LaravelModelSettings
 */
class HasSettingsObserver
{

    /**
     * @var \Yarob\LaravelModelSettings\Services\ModelSettingsService
     */
    private $hasSettingsService;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    private $events;

    /**
     * HasSettingsObserver constructor.
     *
     * @param \Yarob\LaravelModelSettings\Services\ModelSettingsService $modelSettingsService
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    public function __construct(ModelSettingsService $modelSettingsService, Dispatcher $events)
    {
        $this->hasSettingsService = $modelSettingsService;
        $this->events = $events;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return boolean|null
     */
    public function saving(Model $model)
    {
        return $this->saveSettings($model, 'saving');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event
     * @return boolean|null
     */
    protected function saveSettings(Model $model, $event)
    {
        // If the "saving-settings" event returns a value, abort
        if ($this->fireSavingSettingsEvent($model, $event) !== null) {
            return;
        }
        $wasSaved = $this->hasSettingsService->save($model);

        $this->fireSettingsSavedEvent($model, $wasSaved);
    }

    /**
     * Fire the namespaced validating event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $event
     * @return mixed
     */
    protected function fireSavingSettingsEvent(Model $model, $event)
    {
        return $this->events->until('eloquent.saving-settings: ' . get_class($model), [$model, $event]);
    }

    /**
     * Fire the namespaced post-validation event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $status
     * @return void
     */
    protected function fireSettingsSavedEvent(Model $model, $status)
    {
        $this->events->fire('eloquent.settings-saved: ' . get_class($model), [$model, $status]);
    }
}
