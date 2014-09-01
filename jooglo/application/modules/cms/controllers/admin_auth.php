<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
All about admin auth need.

Show login page.
Authentication for admin.
*/

class Admin_auth extends MY_Controller 
{
	public function __construct()
	{		
		parent::__construct();
		
		// Set admin template
		$this->template
			->set_theme('admin')
			->set_css('color_scheme.css');
	}
	
	// Page: Show admin login page
	public function index()
	{
		$role = $this->session->userdata('role_id');
		
		$allowed_role = array(1, 2, 3, 4);
		if (in_array($role, $allowed_role)) 
		{
			redirect('cms/admin/index');
		}
		
		$this->template
			->set_layout('template-login')
			->view('login', $this->data); 
	}
	
	// Post action: handle admin action login
	public function login()
	{
		$check = $this->nyan_auth->login($this->input->post('param1'), $this->input->post('param2'));
			
		if ($check == true)
		{
			# role 1 - 4 to back end
			$role = $this->session->userdata('role_id');
		
			$allowed_role = array(1, 2, 3, 4);
			if (in_array($role, $allowed_role)) 
			{
				redirect('cms/admin/index');
			} 
			else
			{
				redirect();
			}
		}
		else
		{
			$this->session->set_flashdata('message', 'Ooops. Wrong username or password.');
			redirect('adm');
		}
	}
}