<?php

class Labs_Theme_Controller extends Base_Controller {

	public function action_index()
	{
		// test git
		return "Index";
	}

	public function get_view()
	{
		// bbank edit
		return "get view";
	}

	public function get_default()
	{
		$theme = Ioc::resolve('Theme');

		$view = array(
			'first_name' => 'Vasupat',
			'last_name'	 => 'Chantakew'
			);

		return $theme->render('product',$view);
	}

	public function get_blank()
	{
		$theme = Ioc::resolve('Theme');
		$theme->set_layout('blank');
		
		$view = array(
			'first_name' => 'Vasupat',
			'last_name'	 => 'Chantakew'
			);

		return $theme->render('blog',$view);
	}

	public function get_titanium()
	{
		$theme = IoC::resolve('Theme');
		$theme->set_theme('titanium');

		return $theme->render('page');
	}

	public function get_titanium_page_default()
	{
		$theme = IoC::resolve('Theme');
		$theme->set_theme('titanium');

		$view = array(
			'first_name' => 'Vasupat',
			'last_name'	 => 'Chantakew'
			);

		return $theme->render('blog',$view);
	}

	public function get_block()
	{
		$theme = IoC::resolve('Theme');

		// insert data for partial Block1
		$theme->partial('block1', function($view)
		{
			$view->with('data', array(
					'var1' => 'it.truecorp',
					'var2' => 'thaiajax'
				));
		});

		// insert data for partial Block2
		$theme->partial('block2', function($view)
		{
			$view->with('title', 'Title in partial');
			$view->with('description', 'Description in partial');
		});

		return $theme->render('index');
	}

}