<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Custom_show {

	var $ci = null;
		
	# constructor
	function Custom_show()
	{
		$this->ci =& get_instance();
		$this->ci->load->database();
		
		# load model
		$this->ci->load->model('users/mdl_user');
	}
	
	/*
	Display user name advance control
	*/
	function user_name($by_field, $field_value, $special = true)
	{
		# define username
		if ($by_field == 'username')
		{
			$username = $field_value;
			$user_id = $this->ci->mdl_user->get_user_id($field_value, 'username');
		}
		else if ($by_field == 'id')
		{
			$username = $this->ci->mdl_user->get_username($field_value, 'id');
			$user_id = $field_value;
		}
		else
		{
			return false;
		}
		
		# define laim name
		$nyankod_name = $this->ci->mdl_user->get_user_meta($user_id, 'nyankod_name');
		
		# control
		if (!empty($nyankod_name))
		{
			$username = $nyankod_name;
		}
		
		if($special == true){
			# control special accounts
			$special_account = $this->ci->mdl_user->get_user_meta($user_id, 'special_account');
			
			if (!empty($special_account))
			{
				$username = $username.'  <i class="fa fa-check-circle text-darkcyan"></i>';
			}
		}
		
		return $username;
	}
}


