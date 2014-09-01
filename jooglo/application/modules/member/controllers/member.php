<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
All about USER need.

Functional
- Show a page. User display need. (Profile/Statistic/Dashboard, etc)
- Handle user data post action (native/ajax). For user action only (Update profile, Update password, Etc)
*/

class Member extends Frontend_Controller {

	var $data;
	
	public function __construct()
	{		
		parent::__construct();
		
		// Load image helper
		$this->load->library('image');
		
		// Set template
		$this->template->set_layout('template-user');
	}
	
	/*********************
	Show page: user list
	*********************/
	function index()
	{	
		$this->template->set_layout('template-basic');
		$this->template->view('user-list', $this->data);
	}
	
	/*********************
	Show page: dashboard
               setting	
	*********************/
	function dashboard($page = null, $page_number = 1)
	{	
		if(!$this->nyan_auth->logged_in())
			redirect('u/login?callback='.site_url('u/dashboard'));

		$this->data['page_number'] = $page_number;
		$page_allow = array(null, 'contribution', 'password', 'avatar', 'profile', 'question', 'home', 'notification');
		
		if (in_array($page, $page_allow))
		{
			if ($page == null)
			{
				$page = 'home';
			}
			
			$this->template->set_layout('template-dashboard');
			$this->template->view('user-dashboard-'.$page, $this->data);
		}
		else
		{
			show_404();
		}
	}
	
	/***********************
	Action: edit profile
	        update setting
			update password
	************************/
	function edit($mode = null)
	{
		if ($this->data['logged_in'])
		{
			// Edit password mode
			if ($mode == 'edit-password')
			{
				// Get post
				$param['current'] = post_filter($this->input->post('current'));
				$param['new'] = post_filter($this->input->post('new'));
				$param['confirmation'] = post_filter($this->input->post('confirmation'));
		
				$this->form_validation->set_rules('current', 'Current Password', 'required');
				$this->form_validation->set_rules('new', 'New Password', 'required');
				$this->form_validation->set_rules('confirmation', 'Confirmation Password', 'required');
		
				// Validation
				if ($this->form_validation->run() == FALSE) 
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Silakan lengkapi semua kolom.</div>');
					redirect(urldecode($_GET['callback']));
				}
				else
				{
					// Check password
					$check = $this->mdl_user->is_exist_password($this->session->userdata('id'), $this->nyan_auth->hash_password($param['current']));
					
					if ($check != 1)
					{
						// Current password is wrong
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Password terdahulu salah.</div>');
						redirect(urldecode($_GET['callback']));
					}
					else 
					{
						// Check new password and confirmation password
						if ($param['new'] != $param['confirmation'])
						{
							// confirmation password is not match
							$this->session->set_flashdata('message', '<div class="alert alert-danger">Konfirmasi password tidak cocok.</div>');
							redirect(urldecode($_GET['callback']));
						}
						else
						{
							$op = $this->mdl_user->update_user_password($this->nyan_auth->hash_password($param['new']), $this->session->userdata('id'));
							
							if ($op)
							{
								$this->session->set_flashdata('message', '<div class="alert alert-success">Selamat, password berhasil diperbaharui.</div>');
								redirect(urldecode($_GET['callback']));
							}
						}
					}
				}
			}
			
			// Edit profile mode
			else if ($mode == 'edit-profile')
			{
				// Get post
				$input = array (
					'name' => post_filter($this->input->post('name')),
					'who' => post_filter($this->input->post('who')),
					'about' => post_filter($this->input->post('about')),
					'facebook' => post_filter($this->input->post('facebook')),
					'twitter' => post_filter($this->input->post('twitter')),
					'gplus' => post_filter($this->input->post('gplus')),
					'linkedin' => post_filter($this->input->post('linkedin')),
					'github' => post_filter($this->input->post('github')),
					'web' => post_filter($this->input->post('web'))
				);
				
				// Meta update
				$to_update = array('name','who','about','facebook','twitter','gplus','linkedin','github','web');
				
				foreach ($to_update as $row)
				{
					$this->mdl_user->update_user_meta($row, '', $input[$row], $this->session->userdata('id'));
				}
				
				$this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil diperbaharui.</div>');

				// Show success		
				if (isset($_GET['callback']))
				{
					redirect(urldecode($_GET['callback']));
				}
				else
				{
					redirect('~'.$this->session->userdata('username').'/edit');		
				}
			}
			
			// Edit avatar mode
			else if ($mode == 'edit-avatar')
			{
				$upload_path = './sources/uploads/avatar/';
				$old_avatar = $this->mdl_user->get_user_meta($this->session->userdata('id'), 'avatar');	
				
				$config['file_name'] = random_string('alnum', 25);
				$config['upload_path'] = $upload_path;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_width']  = '1024';
				$config['max_height']  = '1024';
				$config['max_size'] = 10000;
			
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload())
				{
					if (isset($_GET['callback']))
					{
						$this->session->set_flashdata('message', '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>');
						redirect(urldecode($_GET['callback']));
					}
					else
					{
						echo 'failed';		
					}
				}
				else
				{
					$data = array('upload_data' => $this->upload->data());
					
					$this->image->crop_proporsional($upload_path, $data['upload_data']['file_name']);
					$this->image->generate_thumbnail($upload_path, $data['upload_data']['file_name']);
					
					if ($this->mdl_user->update_user_meta('avatar', '', $data['upload_data']['file_name'], $this->session->userdata('id')))
					{
						// Delete original & temporary
						$this->image->delete_file($upload_path.$data['upload_data']['file_name']);
						$this->image->delete_file($upload_path.'croped_'.$data['upload_data']['file_name']);
						
						// Delete old one
						$this->image->delete_file($upload_path.'xs_'.$old_avatar);
						$this->image->delete_file($upload_path.'sm_'.$old_avatar);
						$this->image->delete_file($upload_path.'md_'.$old_avatar);
						$this->image->delete_file($upload_path.'lg_'.$old_avatar);
						$this->image->delete_file($upload_path.'square_'.$old_avatar);
						
						if (isset($_GET['callback']))
						{
							$this->session->set_flashdata('message', '<div class="alert alert-success">Sipp, kamu udah keliatan lebih gaya..</div>');
							redirect(urldecode($_GET['callback']));
						}
						else
						{
							echo 'success';		
						}
					}
				}
			}
			else
			{
				echo 'undefined';
			}
		}
		else
		{
			show_404();
		}
	}
	
	/***********************
	Show page: user profile
	************************/
	function profile($username = null, $page = 'question', $page_number = 1)
	{		
		if ($username == null)
		{
			show_404();
		}
		else
		{
			$username = substr($username, 1);
			
			$check = $this->mdl_user->is_exist_username($username);
				
			if ($check)
			{
				$this->data['username'] = $username;
				$this->data['user_id'] = $this->mdl_user->get_user_id($username, 'username');
				
				// Get questions total
				$this->data['questions_total'] = $this->mdl_discuss->get_questions('total', true, $this->data['user_id']);
				
				// Get total contribution
				$param = array(
					'result' => 'total',
					'user_id' => $this->data['user_id']
				);

				$this->data['contribution_total'] = $this->activities_m->get_activities_devo($param);
				
				// Profile home
				if ($page == 'question')
				{
					// Setting paging
					$this->data['pagination_link'] = pagination_link('~'.$this->data['username'].'/question/', $this->data['questions_total'], 5, $page_number);
					
					// Get data
					$this->data['questions'] = $this->mdl_discuss->get_questions('array', true, $this->data['user_id'], $this->data['pagination_link']['limit'], $this->data['pagination_link']['offset']);
					
					$this->template->view('profile-home', $this->data);
				}
				
				// Contribution page
				elseif ($page == 'contribution')
				{
					// Setting paging
					$this->data['pagination_link'] = pagination_link('~'.$this->data['username'].'/contribution/', $this->data['contribution_total'], 5, $page_number);
					
					// Get data
					$param_two = array(
						'result' => 'array',
						'user_id' => $this->data['user_id'],
						'limit' => $this->data['pagination_link']['limit'],
						'limit_order' => $this->data['pagination_link']['offset']
					);
					
					$this->data['contribution'] = $this->activities_m->get_activities_devo($param_two);
					 
					$this->template->view('profile-contribution', $this->data);
				}
				else
				{
					show_404();
				}
			}
			else
			{
				show_404();
			}
		}
	}
	
	/***********************
	Action: logout
	************************/
	public function logout()
	{
		$this->session->sess_destroy();
		redirect();
	}	
	
	/***********************
	Show page: login page
	Action: login post action
	************************/
	function login($mode = null)
	{
		if($this->nyan_auth->logged_in())
			redirect();

		if ($mode == 'do')
		{	
			/* Csrf token check
			if ($this->input->post('action_token') != $this->session->userdata('action_token'))
			{
				echo 'token error.';
				exit
			}
			*/
			
			// Update token
			$this->session->set_userdata('action_token', random_string('alnum', 7));
				
			// Get post
			$callback = urldecode($this->input->post('callback'));
			$error_callback = urldecode($this->input->post('error_callback'));
			$type = $this->input->post('type');
			$param1 = $this->input->post('param1');
			$param2 = $this->input->post('param2');
				
			$login = $this->nyan_auth->login($param1 , $param2);
				
			// If valid
			if ($login)
			{
				if (!empty($type) && $type == 'popup')
				{
					$this->session->set_flashdata('login_message', '<script>window.opener.location.reload();window.close();</script>');
					redirect($error_callback);	
				}
				else
				{
					if (!empty($callback))
					{
						// Return to callback
						redirect($callback);
					}
					else
					{
						// Return to home
						redirect();
					}	
				}
			}
			
			// If not valid
			else
			{
				// If not valid check maybe his/her account is not actived
				$status = $this->mdl_user->get_field_value('status', 'username', $param1);
				if ($status == 'inactive')
				{
					$email = $this->mdl_user->get_field_value('email', 'username', $param1);
					
					$this->session->set_flashdata('login_message', 'Kamu belum melakukan verifikasi via email. Coba cek lagi inbox email kamu dan 
					kemudian klik link konfirmasi untuk mengaktifkan account (periksa juga spam).<br/><br/>
					Email : <b>'.$email.'</b><br/><br/>
					<a>Butuh bantuan?</a>');
					redirect($error_callback);	
				}
				
				$this->session->set_flashdata('login_message', 'Username/Password yang kamu masukan salah. Coba lagi. <a>Butuh bantuan?</a>');
				redirect($error_callback);	
			}		
		}
		else if ($mode == 'popup')
		{
			$this->template->set_layout('template-popup');
			$this->template->view('login-popup', $this->data);
		}
		else
		{
			$this->template->set_layout('template-minimal');
			$this->template->view('login', $this->data);
		}
	}
	
	/***********************
	Show page: register page
	Action: register post action
	************************/
	function register($param = null)
	{
		if ($this->session->userdata('logged_in')) redirect();
		
		if ($param == 'do')
		{			
			// If callback is null, the post is ajax.
			$callback = $this->input->post('callback');

			// Global validation
			$this->load->library('form_validation');

			$this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|max_length[20]|is_unique[jooglo_users.username]|alpha_numeric');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[jooglo_users.email]');
			$this->form_validation->set_rules('password', 'Password', 'required|matches[confirm_password]');
			$this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required');

			if ($this->form_validation->run())
			{
				// Lets get post
				$data['username'] = $this->input->post('username');
				$data['email'] = $this->input->post('email');
				$data['password'] = $this->input->post('password');
				$data['confirm_password'] = $this->input->post('confirm_password');
				$data['verification'] = $this->input->post('verification');
				$data['third_parties_id'] = $this->input->post('third_parties_id'); // Additional if connected with third parties website
				$data['third_parties_source'] = $this->input->post('third_parties_source'); // Additional if connected with third parties website
			
				// Do register
				if ($this->mdl_user->new_user($data, 5, 'inactive'))
				{
					// If register with third parties, add some data to mark there was from third parties.
					if (!empty($data['third_parties_id']) && !empty($data['third_parties_source']))
					{
						$user_id = $this->mdl_user->get_user_id($data['username'], 'username');
						
						if ($data['third_parties_source'] == 'twitter') 
						{
							$this->mdl_user->update_user_meta('tw_id', 'Twitter ID', $data['third_parties_id'], $user_id);;
						}
						else if ($data['third_parties_source'] == 'facebook')
						{
							$this->mdl_user->update_user_meta('fb_id', 'Facebook ID', $data['third_parties_id'], $user_id);;
						}
						else
						{
							echo 'Can not define third parties source.';
						}
					}
					
					// Unset third parties session
					$this->session->unset_userdata('username');
					$this->session->unset_userdata('id');
					$this->session->unset_userdata('source');
					
					if ($this->input->is_ajax_request())
					{
						echo json_encode(array('status' => 'success', 'message' => 'Terima kasih sudah mendaftar. Silakan cek inbox email untuk aktivasi akun.'));
					}
					else
					{
						$this->session->set_flashdata('success_message', 'Terima kasih sudah mendaftar, "'.$data['username'].'". Silakan cek inbox email untuk aktivasi akun.');
						redirect($callback);
					}
				}	
			} 
			else 
			{
				if ($this->input->is_ajax_request())
				{
					echo json_encode(array('status'=>'error', 'message' => validation_errors()));
					exit;
				}
				else
				{
					$this->session->set_flashdata('error_message', validation_errors());
					redirect($callback);
				}
			}
		}
		else
		{
			// Just show registration page.
			$this->template->set_layout('template-minimal');
			$this->template->view('register', $this->data);
		}
	}

	/***********************
	Show page: search user
	************************/
	function search()
	{	
		// Get post
		$keyword = post_filter($this->input->post('username'));
		
		$this->data['total_result'] = $this->mdl_user->search_user_all($keyword, 'total');
		$this->data['result'] = $this->mdl_user->search_user_all($keyword, 'array');
		$this->data['keyword'] = $keyword;
		
		$this->template->set_layout('template-basic');
		$this->template->view('user-search-result', $this->data);
	}
	
	/****************************
	Show page: forgot password
	Action: recovery post action
	*****************************/
	function forgot($mode = null, $token = null)
	{	
		if ($mode == 'request')
		{
			// Get post
			$email = post_filter($this->input->post('email'));
			
			if (empty($email))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Email tidak boleh kosong.</div>');
				redirect('u/forgot');
			}
			
			if (!$this->mdl_user->is_exist_email($email))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Email belum terdaftar.</div>');
				redirect('u/forgot');
			}
				
			// Request new password.
			$this->nyan_auth->reset_password_confirmation($email, 'send-link');
			$this->session->set_flashdata('message', '<div class="alert alert-success">Silakan cek email untuk tautan reset password.</div>');
			redirect('u/forgot');
		}
		else if ($mode == 'confirmation')
		{
			// Get post
			$user_id = $this->mdl_user->get_user_id_by_meta('token', $token);
			
			if (empty($user_id))
			{
				echo 'Invalid token.';
			}
			else
			{
				$result = $this->nyan_auth->reset_password($user_id);
				
				if ($result['message'] == 'done')
				{
					$email = $this->mdl_user->get_user_mail($user_id, 'id');
					$this->nyan_auth->reset_password_confirmation($email, 'reset-completed', $result['password']);
					
					echo '<div style="font-family:arial;font-weight:bold;font-size:16px;background:#5DBDAA;color:#fff;padding:10px;">Kami telah mengirimkan password baru ke email Kamu. Silakan dicek.</div>';
				}
			}
		}
		else
		{
			$this->template->set_layout('template-minimal');
			$this->template->view('forgot', $this->data);
		}
	}
	
	/***********************
	Action: user activation
	************************/
	function activation($token = null)
	{
		$user_id = $this->mdl_user->get_user_id_by_meta('token', $token);
			
		if (empty($user_id))
		{
			echo '<b>Maaf</b>, ada kesalahan teknis. <br/><br/>
			<ul>
			<li>
			Tautan ini adalah tautan aktifasi user yang hanya bisa diakses sekali. Kemungkinan kamu sudah mengakses tautan ini sebelumnya dan
			akun kamu sudah aktif. Mohon kamu coba untuk login.
			</li>
			<li>
			Jika kamu masih kesulitan. Mohon kontak admin/support kami melalui email : <b>support@devository.com</b>
			</li>
			</ul>
			<br/>
	        Terimakasih.';
		}
		else
		{
			// activate the user
			$this->mdl_user->activate_user($user_id);
			
			// reset user token
			$this->mdl_user->update_user_token($user_id);
			
			// force login
			$op = $this->nyan_auth->force_login($user_id);
			
			if ($op == true)
			{
				redirect();
			}
		}
	}
}