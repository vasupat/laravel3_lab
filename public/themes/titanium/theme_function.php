<?php

function theme_titanium(Theme $theme){

	$theme->set_property('title', 'Test set title property');

    // Set css default
    $theme->add_asset('style.css','themes/default/assets/css');
    // Set this theme
    $theme->add_asset('titanium_style.css');
    
    $theme->container('header')->add_asset('
		<script type="text/javascript">var BASE = "'.URL::base().'/'.'";</script>
	', null, 'content');

    $theme->container('footer')->add_asset('jquery.js', 'vendor/scripts');

    // Do your logic when partial "navigator" render.
	$theme->partial('footer', function($view)
	{
		$view->with('copyright', 'weloveshopping.com');
	});

}
