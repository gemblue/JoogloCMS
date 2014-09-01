<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| Parent class of every cms controller.
*/

class MY_Controller extends CI_Controller
{
	public $url_source;
	var $data = null;
	var $template;

	public function __construct()
	{
		parent::__construct();

		// Set timezone
		date_default_timezone_set('Asia/Jakarta');

		/* 
		Check for database updates
		$this->load->library('migration');
		if ( ! $this->migration->latest())
			show_error($this->migration->error_string());
		*/
		
		// Load member module
		$this->load->library('member/nyan_auth');
		
		// Load cms module
		$this->load->model('cms/mdl_post');
		$this->load->model('cms/mdl_taxonomy');
		$this->load->model('cms/mdl_entries');
		$this->load->model('cms/mdl_options');
		
		// Load needed lib
		$this->load->library('custom_page_template');
		$this->load->library('mobile_detect');
		$this->load->library('custom_show');
		$this->load->library('paging');
		
		// Load comment module
		$this->load->model('comment/mdl_comment');
		
		// Load module manager
		$this->load->library('module_manager/module');
		
		// Load Language
		$this->lang->load('jooglo', 'english');
		
		// Load helper
		$this->load->helper('my_string');
		$this->load->helper('pagination');
		
		// Template
		$template_name = $this->mdl_options->get_options('template');
		$this->data['template_name'] = $template_name;
		$this->data['template_path'] = base_url('jooglo/application/themes/'.$template_name.'/');
	
		// Site data
		$this->data['site_title'] = $this->mdl_options->get_options('site_title');
		$this->data['site_template'] = $this->mdl_options->get_options('template');
		$this->data['logo_form_admin'] = $this->mdl_options->get_options('logo_form_admin');
		$this->data['current_url_encode'] = urlencode(base64_encode(current_url()));
		
		// User identification
		$this->data['avatar'] = $this->mdl_user->get_avatar($this->session->userdata('id'));
		$this->data['user_id'] = $this->session->userdata('id');
		$this->data['role_id'] = $this->session->userdata('role_id');
		$this->data['logged_in'] = $this->session->userdata('logged_in');
	
		// Profiler
		$this->output->enable_profiler(false);
	}
}