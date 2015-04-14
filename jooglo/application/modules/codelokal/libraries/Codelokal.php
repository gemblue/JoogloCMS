<?php

/* 
Api codelokal for theming
*/

class codelokal{

	var $ci;
	
	function codelokal()
	{
		// Get instance
		$this->ci =& get_instance(); 
		$this->ci->load->model('codelokal/star_m'); 
	}
	
	// Get star count.
	function get_accumulative($object_id)
	{
		$total = $this->ci->star_m->get_accumulative($object_id);
		
		if ($total == null)
		{
			return '0';
		}
		else
		{
			return	$total;
		}			
	}
	
	// Check is user give star
	function is_starred($user_id, $object_id)
	{
		return $this->ci->star_m->is_starred($user_id, $object_id);
	}
}