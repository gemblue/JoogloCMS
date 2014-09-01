<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Action extends MX_Controller {

	public function __construct()
	{		
		parent::__construct();
		
		# load model 
		$this->load->model('activities_m');	
		
		# load core cms model
		$this->load->model('mdl_post');	
	}
	
	
	/*
	We use index only for redirect
	*/
	function index()
	{
		show_404();
	}
	
	public function _remap()
	{
		show_404();
	}
	
	/*
	Check in door
	function check_in($url,$slug)
    {	
		$original_url = $url;
		$url = base64_decode(urldecode($original_url));
		$id_user = $this->session->userdata('id');
		
		# protect this module, only for authenticated user
		$cek = $this->session->userdata('logged_in');
		if ($cek != 'hore')
		{
			redirect('authentication/login/'.$original_url);	
		}
	
		#get id post by slug
		$id_post = $this->mdl_post->get_id_post($slug);
		
		# add activities check in 
		$op = $this->activities_m->insert_activities($id_user,'Post',$id_post,'checkin','','lalights');
		
		if ($op == true)
		{
			$this->session->set_flashdata('message_check_in', '<div class="alert alert-success">Congratulations.. You have check in!</div>');
			redirect($url);
		}
		else
		{
			$this->session->set_flashdata('message_check_in', '<div class="alert alert-error">You have already check in to this article!</div>');
			redirect($url);
		}
		
	}
	*/
	
	
	
}