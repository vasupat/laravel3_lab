<?php

class Theme extends Theme\Theme {

	/**
	 * Current container.
	 *
	 * @var array
	 */
	private $container  = 'default';

	/**
	 * All of the instantiated asset containers.
	 *
	 * @var array
	 */
	private $containers = array();

	/**
	 * All of the widgets.
	 *
	 * @var array
	 */
	private $widgets = array();

	/**
	 * Scripts.
	 *
	 * @var array
	 */
	private $scripts = array();

	/**
	 * Styles
	 *
	 * @var array
	 */
	private $styles = array();

	/**
	 * Metas
	 *
	 * @var array
	 */
	private $metas = array();

	/**
	 * Properties such as title, description, keywords, etc.
	 *
	 * @var array
	 */
	private $properties = array();

	/**
	 * Theme block
	 *
	 * @var array
	 */
	public $_theme_blocks;

	/**
	 * Construct.
	 *
	 * Register and flusher events.
	 *
	 * @param string $theme_name
	 * @param array  $config
	 */
	public function __construct($theme_name, $config = null)
	{
		parent::__construct($theme_name, $config);

		// Scope.
		$that = $this;

		// Action on flush.
		Event::flusher('assign_asset', function($content, $args) use ($that)
		{
			// Shift container off.
			$container = array_shift($args);

			// What method?
			$call = (preg_match('~\.js|<script|function~', $content)) ? 'add_js' : 'add_css';

			// Re-assign a container if exists.
			if ($container)
			{
				$that = $that->container($container);
			}

			// Call to the real method.
			call_user_func_array(array($that, $call), $args);
		});

		// Event to set properties.
		Event::flusher('assign_property', function($content, $args) use ($that)
		{
			$that->set_property($args[0], $args[1]);
		});

		// Event to append metas.
		Event::flusher('assign_meta', function($content, $args) use ($that)
		{
			$that->add_meta($args[0], $args[1]);
		});
	}

	/**
	 * Return a paths of theme
	 *
	 * @param  string $theme_name
	 * @return array
	 */
	public function path($theme_name = null)
	{
		if (is_null($theme_name))
		{
			$theme_name = $this->_theme_name;
		}

		$base_path = path('public');
		$theme_path_relative = $this->_theme_path . DS . $theme_name ;
		$theme_path_absolute = $base_path . $theme_path_relative;

		return array($theme_path_relative, $theme_path_absolute);
	}

	/**
	 * Register composer event to a view
	 *
	 * for registering a composer to a theme partial
	 *
	 * $theme->composer('menu', function($view){
	 *		 $view->with('theme_menu', "This is loaded from composer of the theme.");
	 *	});
	 *
	 * @param  string|array	 $view
	 * @param  Closure		 $composer
	 * @params string		 $region
	 * @return void
	 */
	public function composer($views, $composer, $region = 'partials')
	{
		// Typecast array
		$views = (array) $views;

		/*$theme_name = $this->_theme_name;
		$base_path = path('public');
		$theme_path_relative = $this->_theme_path . DS . $theme_name ;
		$theme_path_absolute = $base_path . $theme_path_relative;*/

		list ($theme_path_relative, $theme_path_absolute) = $this->path();

		$theme_partials_path_absolute =  $theme_path_absolute . DS. $region;

		foreach ($views as $view)
		{
			// Finding in a sub-path.
			if (strpos($view, '.') !== false)
			{
				$view = str_replace('.', '/', $view);
			}

			if (file_exists($tpath = $theme_partials_path_absolute.DS.$view. EXT))
			{
				$view = "path: " . $tpath;
			}
			elseif (file_exists($tpath = $theme_partials_path_absolute.DS.$view. BLADE_EXT))
			{
				$view = "path: " . $tpath;
			}

			View::composer($view, $composer);
		}
	}

	/**
	 * Shortcut of composer partial
	 *
	 * @param	string|array	$views
	 * @param   Closure			$composer
	 * @return  void
	 */
	public function partial($views, $composer)
	{
		$this->composer($views, $composer, 'partials');
	}

	/**
	 * Shortcut of composer widget
	 *
	 * @param	string|array	$views
	 * @param   Closure			$composer
	 * @return  void
	 */
	public function widget($views, $composer)
	{
		$this->composer($views, $composer, 'widgets');
	}

	/**
	 * Find assets theme path.
	 *
	 * @param  string $path
	 * @param  string $file
	 * @param  string $type
	 * @return string
	 */
	public function asset_path($path = null, $file, $type = null)
	{
		$theme_path_relative = $this->_theme_path;

        $theme = $this->_theme_name;

        if (is_null($type))
        {
	        $type = ends_with($file, '.css') ? 'css' : 'js';
        }

        //directory path set
        if ($path === null)
        {
        	$type_of_content_path = ($type) ? '/' . $type : '';
            $directory = $theme_path_relative .DS. $theme . '/assets' . $type_of_content_path . '/';
        }
        else
        {
            $directory = $path . '/';
        }

        if (preg_match('~^(http)~', $file))
        {
	        $directory = '';
        }

        $asset = $directory . $file;

        return ($path === null) ? URL::to($asset, null, true) : URL::to_asset($asset);
	}

	/**
	 * Set properties
	 *
	 * This method use to set default in theme_function.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function set_property($name, $value)
	{
		$this->properties[$name] = $value;
	}

	/**
	 * Set properties.
	 *
	 * eg. $theme->set('title', 'Some title')
	 * This method use in any route.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function set($name, $value)
	{
		$arguments = func_get_args();

		// Use event to do before render theme.
		Event::queue('assign_property', $name, array($arguments));
	}

	/**
	 * Add meta tag.
	 *
	 * This method use to set default meta on theme_function.
	 *
	 * @param  string $property
	 * @param  string $value
	 * @param  string $key
	 * @return Theme
	 */
	public function add_meta($property, $value, $key = 'property')
	{
		$meta = '<meta '.$key.'="'.$property.'" content="'.$value.'">';

		if ( ! in_array($meta, $this->metas))
		{
			$this->metas[] = $meta;
		}

		return $this;
	}

	/**
	 * Append meta tag.
	 *
	 * eg. $theme->append_meta('og:type', 'type')
	 * This method use in any route.
	 *
	 * @param  string $property
	 * @param  string $value
	 * @param  string $key
	 * @return Theme
	 */
	public function append_meta()
	{
		// Get all arguments.
		$arguments = func_get_args();

		// Firt argument is a queue key.
		$content = $arguments[0];

		// Register queue to do before render theme.
		Event::queue('assign_meta', $content, array($arguments));
	}

	/**
	 * Add JavaScript to container.
	 *
	 * @param  string $content
	 * @param  string $path
	 * @param  string $type
	 * @param  mixed  $defer
	 * @return Theme
	 */
	public function add_js($content, $path = null, $type = 'import', $defer = false)
	{
		switch ($type)
		{
			case 'import':
				$filepath = $this->asset_path($path, $content);
				$js = '<script src="'. $filepath .'"';
				if ($defer)
				{
					$js .= ' defer="defer"';
				}
				$js .= "></script>";
				break;

			case 'embed':
				$js = '<script';
				if ($defer)
				{
					$js .= ' defer="defer"';
				}
				$js .= ">";
				$js .= $content;
				$js .= '</script>';
				break;

			case 'content':
				$js = $content;
				break;
		}

		$this->add_container_regions('scripts');

		if ( ! in_array($js, $this->scripts))
		{
			$this->containers[$this->container]['scripts'] .= $js . "\n";
			$this->scripts[] = $js;

			// Reset container.
			$this->container(null);
		}

		return $this;
	}

	/**
	 * Add Stylesheet to container.
	 *
	 * @param  string $content
	 * @param  string $path
	 * @param  string $type
	 * @param  mixed  $media
	 * @return Theme
	 */
	public function add_css($content, $path = null, $type = 'link', $media = 'all')
	{
		switch ($type)
		{
			case 'link':
				$filepath = $this->asset_path($path, $content);
				$css = '<link href="'. $filepath .'"';
				if ($media)
				{
					$css .= ' media="'. $media .'"';
				}
				$css .= ' type="text/css" rel="stylesheet">' . "\n";
				break;

			case 'import':
				$filepath = $this->asset_path($path, $content);
				$css = '<style type="text/css">@import url('. $filepath .');</style>' . "\n";
				break;

			case 'embed':
				$css = '<style type="text/css">';
				$css .= $content;
				$css .= '</style>' . "\n";
				break;

			case 'content':
				$css = $content;
				break;
		}

		$this->add_container_regions('styles');

		if ( ! in_array($css, $this->styles))
		{
			$this->containers[$this->container]['styles'] .= $css . "\n";

			$this->styles[] = $css;

			// Reset container.
			$this->container(null);
		}

		return $this;
	}

	/**
	 * Add js/css to container
	 *
	 * This method overide parent, but parameters you can see @ add_js, add_css
	 * in above.
	 *
	 * @param  string $filename
	 * @param  string $path
	 * @return string
	 */
	public function add_asset($filename, $path = null)
	{
		$args = func_get_args();

		// Content path, script, style
		$content = $args[0];

		// What method?
		$call = (preg_match('~\.js|<script|function~', $content)) ? 'add_js' : 'add_css';

		return call_user_func_array(array($this, $call), $args);
	}

	/**
	 * Append js/css to containers.
	 *
	 * This method call add_js or add_css to queue adding
	 * assets before rendering output.
	 *
	 * @return void
	 */
	public function append_asset()
	{
		// Get all arguments.
		$args = func_get_args();

		// Firt argument is a queue key.
		$content = $args[0];

		// Container of asset.
		$container = $this->container;

		// Add container to arguments.
		$arguments = array_merge(array($container), $args);

		// Register queue to do before rendering theme.
		Event::queue('assign_asset', $content, array($arguments));
	}

	/**
	 * Set current container.
	 *
	 * @param  string $container
	 * @return Theme
	 */
	public function container($container = 'default')
	{
		$this->container = $container;

		if (! isset($this->containers[$container]))
		{
			$this->containers[$container] = array();
		}
		return $this;
	}

	/**
	 * Add a region to containers.
	 *
	 * @param  string $region
	 * @param  string $container
	 * @return void
	 */
	private function add_container_regions($region, $container = null)
	{
		$container || $container = $this->container;
		if (! isset($this->containers[$container][$region]))
		{
			$this->containers[$container][$region] = null;
		}
	}

	/**
	 * Get all data from containers include scripts and styles.
	 *
	 * @return array
	 */
	private function containers()
	{
		$data = $this->containers;

		if (count($this->scripts))
		{
			$data['scripts'] = @implode("\n", $this->scripts);
		}

		if (count($this->styles))
		{
			$data['styles']  = @implode("\n", $this->styles);
		}

		return $data;
	}

	/**
     * Override render the theme
     *
     * @param string    $view
     * @param array     $data
     */
    public function render($page, $data = null)
    {
    	// Get parent render then buffer into variable.
		$view = parent::render($page, $data);

		if ($view)
		{
			// After render do queues.
			Event::flush('assign_asset');
			Event::flush('assign_property');
			Event::flush('assign_meta');

			// Get all data from containers
			$containers = $this->containers();

			// Unset null keys.
			unset($containers[null]);

			// Append metas.
			$metas['metas'] = implode("\n", $this->metas);

			// All properties.
			$properties = $this->properties;

			// Merge theme data with containers data
			$theme_data = array_merge(View::$shared['theme_data'], $containers, $metas, $properties);

			// This is not important anymore.
			unset($theme_data['metadata']);

			// Re-public share data
			View::share('theme_data', $theme_data);
		}

		// return buffer to render
		return $view;
    }

	/**
	 * Render widget
	 *
	 * Make the widget with object cache, use event to
	 * passing data.
	 *
	 * Example
	 * Event::listen('theme.widget: [widget]', function($args) ...
	 *
	 * @param 	mixed 	$widget
	 * @param 	array 	$data
	 * @return 	string
	 */
	public function render_widget($widget, $data = array())
	{
		list ($theme_path_relative, $theme_path_absolute) = $this->path();

		// Object key cache.
		$w = $widget.'['.http_build_query($data).']';

		// First find from cache, if not exists
		// find in a folder widgets.
		if ( ! $view = array_get($this->widgets, $w))
		{
			// Firing event with arguments pass.
			$contents = Event::fire('theme.widget: '.$widget, array($data));

			// Mixed data from an event.
			if ( ! empty($contents))
			{
				$reprocess = array();
				for ($i = 0; $i < count($contents); $i++)
				{
					$reprocess = array_merge($reprocess, $contents[$i]);
				}

				$data = array_merge($data, $reprocess);
			}

			if (View::exists($widget))
			{
				$view = render($widget, $data);
			}
			else
			{
				// Finding in a sub-path.
				if (strpos($widget, '.') !== false)
				{
					$widget = str_replace('.', '/', $widget);
				}

				//check if widget is available from theme
				if (file_exists($path = $theme_path_absolute .DS. 'widgets'.DS.$widget. EXT))
				{
					$view = View::make("path: " . $path, $data);
				}
				elseif (file_exists($path = $theme_path_absolute .DS. 'widgets'.DS.$widget. BLADE_EXT))
				{
					$view = View::make("path: " . $path, $data);
				}
			}

			// Save in to object.
			array_set($this->widgets, $w, $view);
		}

		return $view;
	}

	/**
	 * Return block view rendered
	 *
	 * @param string $block
	 * @param array $data
	 */
	
	/*
	public function render_block($block, $datas = array())
	{
		//alert($data); die();
		$theme_blocks = $this->_theme_blocks['blocks'];

		$theme_name = $this->_theme_name;
		$base_path = path('public');
		$theme_path_relative = $this->_theme_path . DS . $theme_name ;
		$theme_path_absolute = $base_path . $theme_path_relative;

		$theme_p = array();
		$data['data'] = $datas;

		if ( ! empty($theme_blocks))
		{
			$theme_p = array_keys($theme_blocks);
		}

		if (in_array($block, $theme_p))
		{

			//try to load the block of the theme
			if (file_exists($tpath = $theme_path_absolute .DS. 'blocks'.DS.$block. EXT))
			{
				return	 View::make("path: " . $tpath, $data);
			}
			elseif (file_exists($tpath = $theme_path_absolute .DS. 'blocks'.DS.$block. BLADE_EXT))
			{
				return View::make("path: " . $tpath, $data);
			}

		}
		else
		{

			//try to load the block view from laravel
			if (View::exists($block))
			{
				return render($block, $data);
			}
			else
			{
				//check if block is available from theme
				if (file_exists($tpath = $theme_path_absolute .DS. 'blocks'.DS.$block. EXT))
				{
					return	 View::make("path: " . $tpath, $data);
				}
				elseif (file_exists($tpath = $theme_path_absolute .DS. 'blocks'.DS.$block. BLADE_EXT))
				{
					return View::make("path: " . $tpath, $data);
				}
			}
		}

		return false;
	}
	*/

}