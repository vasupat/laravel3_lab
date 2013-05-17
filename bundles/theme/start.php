<?php

/*
|--------------------------------------------------------------------------
| Auto-Loader Mappings
|--------------------------------------------------------------------------
|
| Laravel uses a simple array of class to path mappings to drive the class
| auto-loader. This simple approach helps avoid the performance problems
| of searching through directories by convention.
|
| Registering a mapping couldn't be easier. Just pass an array of class
| to path maps into the "map" function of Autoloader. Then, when you
| want to use that class, just use it. It's simple!
|
*/

// Path with namespace to core.
Autoloader::namespaces(array(
	'Theme' => Bundle::path('theme').'src'
));

// Path to write widget.
Autoloader::directories(array(
	path('app').'widgets'
));

// Map file to use as a singleton.
Autoloader::map(array(
	'Theme'  => Bundle::path('theme').'theme.php',
	'Widget' => Bundle::path('theme').'widget.php'
));

// Helper function.
require 'src/helpers.php';