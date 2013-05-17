<?php

/*
|--------------------------------------------------------------------------
| Custom blade matchers
|--------------------------------------------------------------------------
|
| Support echos multi line to dressing the code.
|
*/

Blade::extend(function($value)
{
	// Rewrites Blade comments into PHP comments.
	$value = preg_replace('/\{\{--(.+?)(--\}\})?\n/', "<?php // $1 ?>", $value);
	$value = preg_replace('/\{\{--((.|\s)*?)--\}\}/', "<?php /* $1 */ ?>\n", $value);

	// Rewrites Blade echo statements into PHP echo statements.
	$value = preg_replace('/\{\{((.|\s)*?)\}\}/', "<?php echo $1; ?>\n", $value);

	return $value;
});