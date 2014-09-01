<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| Basic parent controller for all the controller need auth to access.
*/

class Auth_Controller extends MY_Controller
{
	function __construct()
	{
        parent::__construct();

		# check if user has login
		if(! $this->nyan_auth->logged_in())
			redirect();
	}
}