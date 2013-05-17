<?php

/**
 * Print the given value and kill the script.
 *
 * @param  mixed  $value
 * @return void
 */
function alert($value, $die = false)
{
	echo "<pre>";
	print_r($value);
	echo "</pre>";
	if ($die) die;
}

/**
 * Dump the given value and kill the script.
 *
 * @param  mixed  $value
 * @return void
 */
function dump($value, $die = false)
{
	echo "<pre>";
	var_dump($value);
	echo "</pre>";
	if ($die) die;
}