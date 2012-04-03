<?php
/**
 * Settings application component
 */
class XSettings extends CApplicationComponent
{
	/**
	 * Private key for storing the cached keys
	 */
	const CACHE_KEY_PREFIX = 'Yii.Settings.Component';
	/**
	 * @var array the settings array
	 */
	public $settings = array();
	/**
	 * @var string the cache component ID
	 */
	public $cacheID = 'cache';
	/**
	 * @var int the duration to cache the items
	 */
	public $cachingDuration = 1440;
	/**
	 * Run init
	 */
	public function init()
	{	
		if($this->cachingDuration>0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key=self::CACHE_KEY_PREFIX;
			if(($data=$cache->get($key))!==false)
			{
				$this->settings = unserialize($data);
				return;
			}
		}
		
		// Load Settings
		$settings = Settings::model()->findAll();
		
		if( count($settings) )
		{
			foreach( $settings as $setting )
			{
				$this->settings[ $setting->settingkey ] = $setting->value !== null ? $setting->value : $setting->default_value;
			}
		}

		if(isset($cache))
		{
			$cache->set($key,serialize($this->settings),$this->cachingDuration);
		}

		return $this->settings;
	}
	/**
	 * Get all settings
	 */
	public function getSettings()
	{
		return $this->settings;
	}
	/**
	 * Get setting value by key
	 */
	public function get( $key, $default=null )
	{
		return isset($this->settings[$key]) ? $this->settings[$key] : null;
	}
	/**
	 * Magic method __get()
	 */
	public function __get( $key )
	{
		return $this->get($key);
	}
	/**
	 * Delete cache if exists
	 */
	public function clearCache()
	{
		if($this->cachingDuration>0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key=self::CACHE_KEY_PREFIX;
			$cache->delete($key);
		}
	}	
}