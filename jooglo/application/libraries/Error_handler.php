<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Error_handler {

	# setting
	var $format = 'json';

	function Error_handler()
	{
	
	}
	
	function show($code, $message)
	{
		/*
		Show the error message
		*/
		
		$result = array('error-code' => 'undefined','message' => $message);
		
		if ($this->format == 'json')
		{
			return json_encode($result);
		}
	}
}


