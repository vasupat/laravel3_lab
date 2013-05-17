<?php

/*
|--------------------------------------------------------------------------
| Inject Theme config.
|--------------------------------------------------------------------------
|
| Inject config to set up theme path.
| path to: public/themes/
|
*/

IoC::singleton('Theme', function()
{
    $config = array(
		'theme_path' => 'themes'
    );
    
    return $theme = new Theme('default', $config);
});