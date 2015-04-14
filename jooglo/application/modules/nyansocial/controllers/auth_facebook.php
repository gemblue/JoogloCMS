<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_facebook extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		// Call member module in core cms
		$this->load->model('mdl_user');
		$this->load->model('mdl_role');
		$this->load->library('nyan_auth');
		
		// Load config app
		$this->config->load('nyansocial');
		
		// Load facebook api 
		$fb_config = array(
			'appId'  => $this->config->item('fb_app_id'),
			'secret' => $this->config->item('fb_app_secret')
		);
		$this->load->library('facebook', $fb_config);
	}
	
	//  We don't use index
	function index()
	{
		redirect();
	}

	// Main link to connect facebook
	function connect()
	{
		// If user has logged in, prevent
		if($this->session->userdata('logged_id')) { show_404(); exit; }

    	// Get user data from facebook 
		$fbid = $this->facebook->getUser();

		if ($fbid) {
			try {
				$user_profile = $this->facebook->api('/me');
			} catch (FacebookApiException $e) {
				$fbid = null;
			}
		}

		if ($fbid) {

			// Check, is user registered in our website with the facebook id?
			if ($user_id = $this->mdl_user->get_user_id_by_meta('fb_id', $fbid))
			{
				// There is the facebook id like that, get their user id in our database
				$user_id = $this->mdl_user->get_user_id_by_meta('fb_id', $fbid);
			
				// But wait, is he/she actived?
				$status = $this->mdl_user->get_field_value('status', 'id', $user_id);
				
				if ($status == 'inactive')
				{
					$this->session->set_flashdata('login_message', 'Kamu sudah terdaftar di sistem kami. Tapi kami mendeteksi bahwa Kamu belum melakukan konfirmasi via email. 
					Coba cek lagi inbox email Kamu (periksa juga spam). Kemudian klik link konfirmasi yang pernah dikirim :) . <a>Butuh bantuan?</a>');
					redirect('u/login');
					exit;
				}
				
				// Force login
				$this->nyan_auth->force_login($user_id);
				
				// Additional marker
				$this->session->set_userdata('facebook_on', 'yes');
				
				// Redirect to homepage
				redirect();
			}
			
			// User is not registered, redirect him to register page and bring some data from facebook
			else 
			{
				// Redirect to register template
				$register_data = array(
                   'username'  => $user_profile['name'],
                   'id'     => $fbid,
                   'source' => 'facebook'
				);

				$this->session->set_userdata($register_data);
				
				redirect('u/register');
			}
		}
		else
		{
			// If there is no data from facebook, redirect user to login apps
			$login_url = $this->facebook->getLoginUrl(array(
				'scope' => 'user_about_me'
				));
			redirect($login_url);
		}
	}
}