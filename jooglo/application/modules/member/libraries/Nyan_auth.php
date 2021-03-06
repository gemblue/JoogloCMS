<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Nyan_auth {

	var $ci;
	var $table 			= 'jooglo_users';
	var $field_id 		= 'id';
	var $username_field = 'username';
	var $password_field = 'password';
	var $email_field 	= 'email';
	var $status 		= 'status';
	var $double_check 	= true; // If want login with username or email give true value.
	var $salt 			= '$aJ1t4';
	var $website 		= 'codelokal.codepolitan.com';
	
	/*
	 * Function Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance();
		
		# load model
		$this->ci->load->model('member/mdl_role');
		$this->ci->load->model('member/mdl_user');
	}

	/*
	 * Function logout
	 *
	 * Normal login to website 
	 *
	 * @return 	true : success
	 */
	function logout()
	{
		$this->session->sess_destroy();
		return true;
	}
	
	/*
	 * Function login
	 *
	 * Normal login to website
	 * 
	 * @return 	true : success
	 */
	function login($param1 = null, $param2 = null)
	{
		# hash param2
		$param2 = md5($param2.$this->salt);
				
		# check
		$this->ci->db->from($this->table);
		$this->ci->db->where($this->email_field, $param1);
		$this->ci->db->where($this->password_field, $param2);
		$this->ci->db->where($this->status, 'active');
		$query = $this->ci->db->get();
			
		# user is registered
		if ($query->num_rows() == 1)
		{
			# get user data
			foreach ($query->result() as $row)
			{
				$id = $row->id;
				$username = $row->username;
					
				# define the role
				$role_id = $this->ci->mdl_role->get_user_role($id);
			}
				
			# give session
			$newdata = array(
						 'username'  => $username,
						 'id' => $id,
						 'role_id' => $role_id,
						 'logged_in' => true
					   );
					   
			$this->ci->session->set_userdata($newdata);
			
			# update last login
			$now = date('Y:m:d H:i:s');
			$this->ci->mdl_user->update_user_master($id, 'last_login', $now);
			
			return true;
		}  
		else 
		{
			if ($this->double_check == true)
			{
				# not valid, check from username
				$this->ci->db->from($this->table);
				$this->ci->db->where($this->username_field, $param1);
				$this->ci->db->where($this->password_field, $param2);
				$this->ci->db->where($this->status,'active');
				$query = $this->ci->db->get();
					
				# user is registered
				if ($query->num_rows() == 1)
				{
					# get user data
					foreach ($query->result() as $row)
					{
						$id = $row->id;
						$username = $row->username;
							
						# define the role
						$role_id = $this->ci->mdl_role->get_user_role($id);
					}
						
					# give session
					$newdata = array(
								 'username'  => $username,
								 'id' => $id,
								 'role_id' => $role_id,
								 'logged_in' => true
							   );
					$this->ci->session->set_userdata($newdata);
					
					# update last login
					$now = date('Y:m:d H:i:s');
					$this->ci->mdl_user->update_user_master($id, 'last_login', $now);
					
					return true;
				}  
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
	}
	
	/*
	 * Function force_login
	 *
	 * Login to website with rock! 
	 *
	 * @return 	true : success
	 */
	function force_login($user_id, $force_username = null)
	{
		$username = $this->ci->mdl_user->get_username($user_id, 'id');
		$role_id = $this->ci->mdl_role->get_user_role($user_id);
		
		# override
		if ($force_username != null)
		{
			$username = $force_username;
		}
		
		# give session
		$newdata = array(
			'username'  => $username,
			'id' => $user_id,
			'role_id' => $role_id,
			'logged_in' => true
		);
					   
		$this->ci->session->set_userdata($newdata);
		
		# update last login
		$now = date('Y:m:d H:i:s');
		$this->ci->mdl_user->update_user_master($user_id, 'last_login', $now);
					
		return true;
	}
	
	/*
	 * Function hash_password
	 *
	 * Get hashed password from your password config
	 *
	 * @return : string hashed password
	 */	
	function hash_password($password)
	{
		$password = md5($password.$this->salt);
		return $password;
	}
	
	/*
	 * Function reset_password
	 *
	 * Reset the user password
	 *
	 * @return true : success
	 */
	function reset_password($user_id)
	{
		$this->ci->load->helper('string');
		$password = random_string('alnum', 5);
		$password_hash = md5($password.$this->salt);
		
		$this->ci->mdl_user->update_user_password($password_hash, $user_id);
		$result = array ('message' => 'done', 'password' => $password);
		
		return $result;
	}
	
	/*
	 * Function reset_password_confirmation
	 *
	 * Send the user reset password confirmation
	 *
	 * @return true : success
	 */
	function reset_password_confirmation($email, $type, $password = null)
	{
		$user_id = $this->ci->mdl_user->get_user_id($email, 'email');
		$this->ci->mdl_user->update_user_token($user_id);
	
		$config = Array(
		  'protocol' => 'smtp',
		  'smtp_host' => 'ssl://smtp.googlemail.com',
		  'smtp_port' => 465,
		  'smtp_user' => 'ahmadoriza@gmail.com', // change it to yours
		  'smtp_pass' => 'aku06061990', // change it to yours
		  'mailtype' => 'html',
		  'charset' => 'iso-8859-1',
		  'wordwrap' => TRUE
		);
		
		$this->ci->load->library('email', $config);
		
		if ($type == 'send-link')
		{
			$token = $this->ci->mdl_user->get_user_meta($user_id, 'token');
			
			$msg =  'You have requested to reset your password in <b>'.$this->website.'</b>. Please follow this link below to complete your request <br />
					 <a href='.site_url('u/forgot/confirmation/'.$token).'>'.site_url('u/forgot/confirmation/'.$token).'</a><br/><br/>
					 Please Ignore if you don&acutet.';
					 ;
				
			$subject = 'Password Reset - Request';
		}
		else if ($type == 'reset-completed')
		{
			$msg =  'You have requested to reset your password in <b>'.$this->website.'</b>. Below, your new password <br />'.
					'Password :'.$password.'<br/><br/>Thank you.';
		
			$subject = 'Password Reset - Completed';
		}
		else
		{
			return false;
		}
		
		$this->ci->email->set_newline("\r\n");
		$this->ci->email->from('no-reply@'.$this->website);
		$this->ci->email->to($email);
										
		$this->ci->email->subject($subject);
		$this->ci->email->message($msg);
		$this->ci->email->send();
		
		return true;
	}

	/*
	 * Function logged_in
	 *
	 * check if user logged in
	 *
	 * @return true : success
	 */
	function logged_in()
	{
		return $this->ci->session->userdata('logged_in');
	}

	/*
	 * Function username_check
	 *
	 * Check to make sure username is unique upon create/edit
	 *
	 * @return void
	 */
	function username_check($username)
	{
		return $this->ci->mdl_user->is_exist_username($username);
	}

    /*
	 * Function email_check
	 *
	 * Check to make sure email is unique upon create/edit
	 *
	 * @return void
	 */
    function email_check($email)
    {
    	return $this->ci->mdl_user->is_exist_email($email);
    }
}