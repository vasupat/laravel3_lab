<?php namespace Theme;

abstract class CWidget extends \Laravel\View {
	
	/**
	 * Config to use theme view or default view.
	 *
	 * @var bool
	 */
	public $use_theme = true;
	
	/**
	 * Widget destination path.
	 *
	 * @var string
	 */
	protected $widget_path = 'widgets';
	
	/**
	 * Parameter pass from a widget.
	 *
	 * @var array
	 */
	protected $attributes = array();
	
	/**
	 * Construct of widget view.
	 *
	 * @param  string  $view
	 * @return \Laravel\View
	 */
	public function __construct($view)
	{
		// Use default view path /views/widgets/[$view]
		if ( ! $this->use_theme === true)
		{
			$view = $this->widget_path . '.' . $view;
			
			return parent::__construct($view);
		}
		
		// Get current theme
		$theme = \IoC::resolve('Theme');
		
		list ($theme_path_relative, $theme_path_absolute) = $theme->path();
		
		// The path to theme widget.
		$theme_widgets_path_absolute =  $theme_path_absolute . DS. $this->widget_path;
		
		// Finding in a sub-path.
		if (strpos($view, '.') !== false)
		{
			$view = str_replace('.', '/', $view);
		}
			
		// Lookup for a blade or simple php.
		if (file_exists($tpath = $theme_widgets_path_absolute.DS.$view. EXT)) 
		{
			$view = "path: " . $tpath; 
		} 
		elseif (file_exists($tpath = $theme_widgets_path_absolute.DS.$view. BLADE_EXT)) 
		{
			$view = "path: " . $tpath; 		
		}
	
		return parent::__construct($view);
	}
	
	/**
	 * Abstract class init for a widget factory.
	 * 
	 * @return void
	 */
	abstract public function init();
	
	/**
	 * Abstract class run for a widget factory.
	 * 
	 * @return void
	 */
	abstract public function run();
	
	/**
	 * Set attributes to object var.
	 * 
	 * @param  arary  $attributes
	 * @return void
	 */
	public function setAttributes($attributes)
	{
		$this->attributes = array_merge($this->attributes, $attributes);
	}
	
	/**
	 * Get attributes.
	 * 
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;	
	}
	
	/**
	 * Get attribute with a key.
	 * 
	 * @param  string  $key
	 * @param  string  $default
	 * @return mixed
	 */
	public function getAttribute($key, $default = null)
	{
		return array_get($this->attributes, $key, $default);
	}
	
	/**
	 * Start widget factory.
	 * 
	 * @return void
	 */
	public function beginWidget()
	{
		$this->init();
	}
	
	/**
	 * End widget factory.
	 * 
	 * @return void
	 */
	public function endWidget()
	{
		$data = (array) $this->run();

		$this->data = array_merge($this->attributes, $data);
	}
	
}