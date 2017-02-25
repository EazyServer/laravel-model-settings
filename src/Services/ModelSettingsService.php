<?php

namespace Yarob\LaravelModelSettings\Services;

use ErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Class SlugService
 *
 * @package Cviebrock\EloquentSluggable\Services
 */
class ModelSettingsService
{

    /**
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

	/**
	 * @param \Illuminate\Database\Eloquent\Model $model
	 */
    public function __construct(Model $model=null)
    {
    	if(!empty($model))
    	{
            $this->model = $model;
	    }
    }

	/**
	 * Save settings for current model.
	 *
	 * @param null $settings
	 * @return bool
	 * @throws ErrorException
	 * @internal param Model $model
	 */
    public function save($settings=null)
    {
    	if(!empty($settings) and !empty($this->model))
	    {
	    	if (!Schema::hasColumn($this->model->getTable(), 'settings')) {
	    		throw new ErrorException('"settings" column seems to be missing on "'.class_basename($this->model).'" table on database!');
		    }
		    else
	        {
		        $allowedSettingsKeys = $this->getConfiguration();
				$oldSettings = is_array($this->model->settings)?$this->model->settings:array();

		        $settings = array_merge(
			        $oldSettings,
			        array_only(
			        	$settings,
				        $allowedSettingsKeys
			        )
		        );

		        return $this->model->update(compact('settings'));
		    }
	    }
    }

    /**
     * Get Model settings configuration for the current model,
     *
     * @return array
     */
    public function getConfiguration()
    {
        static $defaultConfig = null;

        if ($defaultConfig === null) {
            $defaultConfig = app('config')->get('model-settings');
        }

        return $defaultConfig[class_basename($this->model)];
    }

}
