<?php

function theme_default(Theme $theme){

	$theme->set_property('title', 'Test set title property');

    $theme->add_asset('style.css');
    
    $theme->container('header')->add_asset('
		<script type="text/javascript">var BASE = "'.URL::base().'/'.'";</script>
	', null, 'content');

    $theme->container('footer')->add_asset('jquery.js', 'vendor/scripts');

    // Do your logic when partial "navigator" render.
	$theme->partial('footer', function($view)
	{
		$view->with('copyright', 'Hi weloveshopping.com');
	});

	//$theme->partial('menu', function($view)
	//{
		//$view->with('var_menu', 'xxxxx');
	//});

}
