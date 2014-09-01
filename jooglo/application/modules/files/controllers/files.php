<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends Frontend_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->data['layout'] = $this->data['template_name'].'basic-layout';
	}

	/*
	 * function index
	 */
	public function index()
	{
		$this->data['title_page'] = 'Frontend File Manager';
		$this->data['body'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque, eligendi, minima, repellat, vel a adipisci eveniet rerum consequatur voluptatibus culpa sequi earum eius? Sint, adipisci, laudantium voluptates accusamus impedit ratione?';

		# render page
		$this->data['content_page'] = 'index';
		$this->load->view($this->data['layout'], $this->data); 
	}

}