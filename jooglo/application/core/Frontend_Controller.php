<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| Parent of the class that don't need logged in to access (public front end)
*/

class Frontend_Controller extends MY_Controller
{
	function __construct()
	{
        parent::__construct();
		
		// Load Jooglo Library
		$this->load->library('cms/jooglo');
		
		// Load Showcase Codepolitan Library
		$this->load->library('codelokal/codelokal');
		$this->load->model('codelokal/star_m');
		
		// Set template
		$this->template->set_theme($this->data['site_template']);
	}
}