<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_twitter_reload extends MY_Controller {
	
	// Force register don't need register page, user will be created automatically 
	var $force_register = true;
	
	public function __construct()
	{
		parent::__construct();
		
		// Load nyansocial config
		$this->config->load('nyansocial');
		
		$this->load->helper('string');
		
		// Load twitter api
		include_once ( APPPATH.'modules/nyansocial/libraries/twitteroauth/twitteroauth.php' ); 
	}
	
	//  We don't use index
	function index()
    {
		redirect();
	}

	// Main link to connect twitter
	function connect()
    {
        $oauth = new TwitterOAuth($this->config->item('tw_consumer_key'),$this->config->item('tw_consumer_secret'));
        $callback = base_url() . 'nyansocial/auth_twitter_reload/callback';
		$oauthRequest = $oauth->getRequestToken($callback);
           
		$this->session->set_userdata("o_tok",$oauthRequest['oauth_token']);
        $this->session->set_userdata("o_tok_secret",$oauthRequest['oauth_token_secret']);
      
		$registerUrl = $oauth->getAuthorizeURL($oauthRequest);
		redirect($registerUrl);
    }
    
	// To receive callback from twitter
    function callback()
	{
		// Take the token data from session
        $o_token = $this->session->userdata('o_tok');
        $o_token_secret = $this->session->userdata('o_tok_secret');

        // Create the connection
        $connection = new TwitterOAuth($this->config->item('tw_consumer_key'), $this->config->item('tw_consumer_secret'), $o_token, $o_token_secret);
		
        // Get user data from twitter
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		$full_data = $connection->get('account/verify_credentials');
		
		/*
		print_r($full_data);
		echo $full_data->name;
		*/
		
		// We have to save session login via and oauth token, this is very helpful for identification 
		$newdata = array(
			'o_token' => $access_token['oauth_token'],
			'o_token_secret' => $access_token['oauth_token_secret']
		);
		
		$this->session->set_userdata($newdata);
		
		// Check, is user registered in our website with the twitter id?
		if ($this->mdl_user->is_meta_value_exist('tw_id', $access_token['user_id'])) 
		{
			// There is the twitter id like that, get their user id in our database
			$user_id = $this->mdl_user->get_user_id_by_meta('tw_id', $access_token['user_id']);
			
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
			$this->session->set_userdata('twitter_on', 'yes');
			
			// Reload
			echo ' <script>window.opener.location.reload();window.close();</script>';
		}

		// User is not registered, redirect him to register page and bring some data from twitter
		else 
		{
			if ($this->force_register == true)
			{
				// Force register mode on
				$uniq = strtolower(random_string('alnum', 5));
				$param = array(
					'username' => $uniq,
					'password' => '',
					'email' => $uniq,
					'role_id' => 5,
					'status' => 'active'
				);
				
				$user_id = $this->mdl_user->insert_user($param);
				$this->nyan_auth->force_login($user_id, $full_data->name);
				
				// Save social media id
				$this->mdl_user->update_user_meta('tw_id', 'Twitter id', $access_token['user_id'], $user_id);
				$this->mdl_user->update_user_meta('name', 'Name', $full_data->name, $user_id);
				
				// Reload
				echo ' <script>window.opener.location.reload();window.close();</script>';
			}
			else
			{
				// Redirect to register template
				$register_data = array(
					'username'  => $access_token['screen_name'],
					'id'     => $access_token['user_id'],
					'source' => 'twitter'
				);
				
				$this->session->set_userdata($register_data);
				redirect('u/register');
			}			
		}
    }
	
	// Function to tweet.
	public function tweet($status)
	{
		$connection = new TwitterOAuth($this->config->item('tw_consumer_key'), $this->config->item('tw_consumer_secret'), $this->session->userdata('o_token'), $this->session->userdata('o_token_secret'));
		$tweet = $connection->post('statuses/update', array('status' => htmlspecialchars($status, ENT_QUOTES)));
		if ($tweet)
			return true;
		
		return false;
	}
}