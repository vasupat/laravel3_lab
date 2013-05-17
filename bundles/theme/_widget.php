<?php

class Widget {

	/**
	 * Instances object.
	 *
	 * @var array
	 */
	private static $instances = array();
	
	/**
	 * Make a widget.
	 *
	 * @param  string  $cls
	 * @param  arary   $attributes
	 * @return Widget
	 */
	public static function make($cls, $attributes = array())
	{		
		// Singleton instance.
		if ( ! $instance = array_get(static::$instances, $cls))
		{	
			$reflector = new \ReflectionClass($cls);
			
			if ( ! $reflector->isInstantiable())
			{
				throw new \Exception("Widget target [$cls] is not instantiable.");
			}
			
			$instance = $reflector->newInstance();
		
			array_set(static::$instances, $cls, $instance);
		}
		
		// Set attributes.
		$instance->setAttributes($attributes);
		
		// Initialize widget factory.
		$instance->beginWidget();

		// Get a widget data.
		$instance->endWidget();
		
		return $instance;
	}
	
}