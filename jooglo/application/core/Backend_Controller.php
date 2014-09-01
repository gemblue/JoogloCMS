<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| Parent class of all backend controller
| Backend controller need logged in
*/

class Backend_Controller extends MY_Controller
{
	function __construct()
	{
        parent::__construct();
	
		// This page is only for administrator role 1 - 4
		$role = $this->session->userdata('role_id');
		$allowed_role = array(1, 2, 3, 4);
		if (!in_array($role, $allowed_role)) show_404();

		// Set theme and template
		$this->template
			->set_theme('admin')
			->set_css('color_scheme.css');
	}
}