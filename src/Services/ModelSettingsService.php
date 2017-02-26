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
	 * @param array $rawSettings
	 * @return bool/collection
	 * @throws ErrorException
	 * @internal param Model $model
	 */
    public function save($rawSettings=array())
    {
    	if(!empty($this->model) and is_array($rawSettings) and !empty($rawSettings))
	    {
	    	if (!Schema::hasColumn($this->model->getTable(), 'settings')) {
	    		throw new ErrorException('`settings` column seems to be missing on `'.$this->model->getTable().'` table on database!');
		    }
		    else
	        {
		        $allowedSettingsKeys = $this->getConfiguration();

				$oldSettings = $this->model->settings->toArray();

		        $newSettings = array_merge(
			        $oldSettings,
			        array_only(
				        $rawSettings,
				        $allowedSettingsKeys
			        )
		        );

		        if($this->model->update( array( 'settings' => json_encode($newSettings) ) ))
		        {
		        	return collect($newSettings);
		        }
		    }
	    }
	    return false;
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
