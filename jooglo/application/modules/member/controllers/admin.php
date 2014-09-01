<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Backend_Controller {

	public function __construct()
	{		
		parent::__construct();

		$this->load->model('users/mdl_user');
		$this->load->model('users/mdl_role');
	}

	/*
	Show user all role
	*/
	public function index($status = 'all')
	{
		# title_page
		$this->template->title('User');
			
		# breadcrumb
		$this->data['breadcrumb'] = array(
			'User' => false
		);

		if($status != 'all'){
			$this->data['breadcrumb']['User'] = base_url('admin/users');
			$this->data['breadcrumb'][ucfirst($status)] = false;
		}
			
		$this->data['num_rows'] = $this->mdl_user->get_tot_user($status);
			
		$config['base_url'] = site_url('admin/users/'.$status);
		$config['total_rows'] = $this->data['num_rows']; 
		$config['per_page'] = 15; 
		$config['uri_segment'] = 4; 
		$config['full_tag_open'] = '<div class="pagination pagination-small pagination-la"><ul>';
		$config['full_tag_close'] = '</ul></div>';
		$config['first_link'] = '<i class="icon-long-arrow-left"></i> First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last <i class="icon-long-arrow-right"></i>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config); # initialize pagination
		$this->data['pagination'] = $this->pagination->create_links();
			
		# query
		$this->data['pg_query'] = $this->mdl_user->get_list_user($status, $config['per_page'], $this->uri->segment(4));
		// print_r($this->data['pg_query']);

		$this->template->view('vw_dash_user_list', $this->data); 
	}
	
	/*
	Show form edit user
	*/
	public function user_edit($id_user)
	{
		# permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		# title_page
		$this->template->title('Edit User');
			
		# breadcrumb
		$this->data['breadcrumb'] = array(
			'User' => 'admin/users',
			'Edit User' => false
		);
			
		$this->data['form_type'] = 'edit';
			
		# query
		$this->data['pg_query'] = $this->mdl_user->get_user($id_user, 'id'); # ambil data2 user by id
		$this->data['role_query'] = $this->mdl_role->get_list_role(); # ambil data2 role
		
		$this->template->view('vw_dash_user_form', $this->data); 
	}
	
	/*
	Update user
	*/
	public function user_update()
	{
		# permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		# action
		$data = array (
					'id_user' => $this->input->post('id_user'),
					'f_name' => $this->input->post('f_name'),
					'l_name' =>$this->input->post('l_name'),
					'address' => $this->input->post('address'),
					'special_account' => $this->input->post('special_account'),
					'biography' => $this->input->post('biography'),
					'role' => $this->input->post('role'),
					'new_pass' => $this->input->post('new_pass'),
					'phone' => $this->input->post('phone')
		        );
				
		# role update		
		if (!empty($data['role']))
		{
			$this->mdl_role->update_user_role($data['id_user'], $data['role']);
		}
		
		# special acccount update		
		$this->mdl_user->update_user_meta('special_account', 'Special Account', $data['special_account'], $data['id_user']);
		
		/*
		# master update
		$this->mdl_user->update_user_master($data['id_user'], '','');
		*/
		
		# password update		
		if (!empty($data['new_pass']))
		{
			$password = $this->nyan_auth->hash_password($data['new_pass']);
			$this->mdl_user->update_user_password($password, $data['id_user']);
		}
		
		# meta update all
		$this->mdl_user->update_user_meta('biography', 'Biography', $data['biography'], $data['id_user']);
		$this->mdl_user->update_user_meta('first_name', 'First Name', $data['f_name'], $data['id_user']);
		$this->mdl_user->update_user_meta('last_name', 'Last Name', $data['l_name'], $data['id_user']);
		$this->mdl_user->update_user_meta('address', 'Address', $data['address'], $data['id_user']);
		$this->mdl_user->update_user_meta('phone', 'Phone', $data['phone'], $data['id_user']);
				
		$this->session->set_flashdata('message', '<div class="alert alert-success">Data has been updated!</div>');
		redirect('admin/users/user_edit/'.$data['id_user']);	
		
	}
	
	/*
	Activate user
	*/
	public function user_activate($id, $url_callback)
	{
		# permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		$username = $this->mdl_user->get_username($id, 'id');
				
		$op = $this->mdl_user->activate_user($id);
		
		if ($op == true)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-success"><strong>'.$username.'</strong> is active now</div>');
			$url_callback = base64_decode(urldecode($url_callback));
			redirect($url_callback);
		}
	}
	
	/*
	Disactive user
	*/
	public function user_disactive($id, $url_callback)
	{
		# permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		$username = $this->mdl_user->get_username($id, 'id');
				
		$op = $this->mdl_user->inactive_user($id);
		
		if ($op == true)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-success"><strong>'.$username.'</strong> is inactive now</div>');
			$url_callback = base64_decode(urldecode($url_callback));
			redirect($url_callback);
		} 
	}
	
	/*
	Search user
	*/
	public function user_search()
	{
		# title_page
		$this->template->title('Search User');
			
		# breadcrumb
		$this->data['breadcrumb'] = array(
			'User' => 'admin/users',
			'Search User' => false
		);
			
		# for label search
		$this->data['search_result'] = 'search';
		
		$username = $this->input->post('inp_search');
		$this->data['keyword'] = $username;
			
		$this->data['num_rows'] = $this->mdl_user->search_user_all($username,'total');
			
		# query
		$this->data['pg_query'] = $this->mdl_user->search_user_all($username,'');

		$this->template->view('vw_dash_user_list', $this->data); 
	}
	
	/*
	Show new form
	*/
	public function user_new()
	{
		# permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		# title_page
		$this->template->title('New User');
			
		# breadcrumb
		$this->data['breadcrumb'] = array(
			'User' => 'admin/users',
			'New User' => false
		);
			
		$this->data['form_type'] = 'new';
			
		# query
		$this->data['role_query'] = $this->mdl_role->get_list_role(); # ambil data2 role
		
		$this->template->view('vw_dash_user_new', $this->data); 
	}
	
	/*
	Add user
	*/
	public function user_add()
	{
		# permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		# title_page
		$this->template->title('New User');
			
		# breadcrumb
		$this->data['breadcrumb'] = array(
			'User' => 'admin/users',
			'New User' => false
		);
		
		# get post
		$param['username'] = $this->input->post('username');
		$param['email'] = $this->input->post('email');
		$param['first_name'] = $this->input->post('f_name');
		$param['last_name'] = $this->input->post('l_name');
		$param['role'] = $this->input->post('role');
		$param['password'] = $this->input->post('password');
		
		# check username
		$check = $this->mdl_user->is_exist_username($param['username']);
		if ($check == 1)
		{	
			$this->session->set_flashdata('message', '<div class="alert alert-error">Username "<strong>'.$param['username']. '</strong>" is already exist</div>');
			redirect('admin/users/user_news');
			exit;
		}
		
		# check email
		$check = $this->mdl_user->is_exist_email($param['email']);
		if ($check == 1)
		{	
			$this->session->set_flashdata('message', '<div class="alert alert-error">Email "<strong>'.$param['email']. '</strong>" is already exist</div>');
			redirect('admin/users/user_news');
			exit;
		}
		
		# validation
		$this->form_validation->set_rules('role', 'Role', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		
		if ($this->form_validation->run() == false) 
		{
			$this->session->set_flashdata('message', '<div class="alert alert-error">Username/password/email/role must be filled</div>');
			redirect('admin/users/user_news');
			exit;
		} 
		
		# insert new user
		$this->mdl_user->new_user($param, false, $param['role'], 'active', false, false);
		$this->session->set_flashdata('message', '<div class="alert alert-success">Successfully add "'.$param['username'].'"</div>');
		redirect('admin/users/user_news');
	}
	
	/*
	Permanent delete
	*/
	public function user_delete($id)
	{
		# permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		# action
		$username = $this->mdl_user->get_username($id, 'id');
		$op = $this->mdl_user->permanent_delete($id);
		if ($op == true)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-success"><strong>'.$username.'</strong> successfully deleted!</div>');
			redirect('admin/users');
		}  
	}

	/*
	Private function to limits user admin action by role
	*/
	private function permission_action($param)
	{
		# get current user role
		$user_role = $this->session->userdata('role_id');
		
		# param are all role can do the action 
		if (in_array($user_role, $param)) 
		{
			return true;
		} 
		else 
		{
			$this->session->set_flashdata('message', '<div class="alert alert-error">Your role is forbidden to do this action.</div>');
			redirect('control/index');
			exit;
		}
	}

}