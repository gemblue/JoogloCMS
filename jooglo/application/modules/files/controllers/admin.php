<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Backend_Controller {

	# setting upload path global
	var $upload_path = 'assets/files/';
	
	public function __construct()
	{
		parent::__construct();

		$this->data['layout'] = 'admin/template-basic';
	}

	/*
	 * function index
	 */
	public function index()
	{
		$this->data['title_page'] = 'File Manager';
		$this->data['content_page'] = 'admin/index';

		# render page
		$this->load->view($this->data['layout'], $this->data); 
	}

}