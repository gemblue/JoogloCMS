<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This is the controller that handle codelokal star action post or request
 
class Star extends MY_Controller 
{
	public function __construct()
	{		
		parent::__construct();
		
		$this->load->model('star_m');
	}
	
	public function insert()
	{
		if ($this->data['user_id'] == null)
		{
			echo 'login_required';
			exit;
		}
		
		$param = array(
			'user_id' => $this->session->userdata('id'),
			'object_id' => $this->input->post('object_id'),
			'created_at' => date('Y-m-d H:i:s')
		);
		
		$total_star = $this->star_m->insert($param);
		
		if ($total_star == false)
		{
			echo 'failed';
		}
		else
		{
			echo 'success';
		}
	}
	
	public function delete()
	{
		if ($this->data['user_id'] == null)
		{
			echo 'login_required';
			exit;
		}
		
		$object_id = $this->input->post('object_id');
		$total_star = $this->star_m->delete($this->session->userdata('id'), $object_id);
		
		if ($total_star == false)
		{
			echo 'failed';
		}
		else
		{
			echo 'success';
		}
	}
}