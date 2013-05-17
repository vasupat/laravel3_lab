<?php

class Labs_Theme_Controller extends Base_Controller {

	public function action_index()
	{
		return "Index";
	}

	public function get_view()
	{
		return "get view";
	}

	public function get_layout()
	{

		$theme = new Theme('default');

		$view = array(
			'first_name'=>'Vasupat',
			'last_name' => 'Chantakeaw'
			);

		return $theme->render('blog', $view);
	}

	public function get_content()
	{

	}

}