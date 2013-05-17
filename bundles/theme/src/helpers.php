<?php

/**
 * Render theme partial.
 * 
 * @param   mixed    $partial
 * @param   array    $data 
 * @return  string
 */
function theme_partial($partial, $data = array())
{ 
	$theme = IoC::resolve('Theme');
	
	return $theme->render_partial($partial, $data); 
}

/**
 * Render theme partial.
 * 
 * @param   mixed    $partial
 * @param   array    $data 
 * @return  string
 */
/*
function theme_block($block, $data = array())
{ 
	$theme = IoC::resolve('Theme');
	
	return $theme->render_block($block, $data); 
}
*/

/**
 * Render theme widget.
 * 
 * @param   mixed    $widget
 * @param   array    $data 
 * @return  string
 */
function theme_widget($widget, $data = array())
{ 
	$theme = IoC::resolve('Theme');
	
	return $theme->render_widget($widget, $data); 
}

/**
 * Get theme asset path.
 * 
 * @param   string   $uri
 * @param   string   $theme_name 
 * @return  string
 */
function theme_asset_path($uri = '', $theme_name = null)
{
	$theme = IoC::resolve('Theme');
	
	if ( ! is_null($theme_name))
	{
		$theme->set_theme($theme_name);
	}
	
	return $theme->asset_path(null, $uri, '');
}