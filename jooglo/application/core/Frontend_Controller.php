<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| Parent of the class that don't need logged in to access (public front end)
*/

class Frontend_Controller extends MY_Controller
{
	function __construct()
	{
        parent::__construct();
		
		/*
		# construction/maintenance mode
		$ip_dev = array('114.79.0.222', '114.79.52.8');
		$current_ip = $_SERVER['REMOTE_ADDR'];
		
		if (!in_array($current_ip, $ip_dev)) {
			redirect('maintenance');
			exit;
		}
		*/
		
		/*
		# construction/maintenance mode by session
		
		if ($this->session->userdata('role_id') != 1)
		{
			redirect('maintenance');
		}
		*/
		
		/* Load minifier
		$this->load->library('minify');
		
		// output path where the compiled files will be stored
		$config['assets_dir'] = 'assets';  

		// where to look for css files 
		$config['css_dir'] = 'assets/css';

		// where to look for js files 
		$config['js_dir'] = 'assets/js'; 

		// add css files  
		$this->minify->css(array('devository_font.css', 'devository_style.css', 'devository_responsive_style.css')); 

		// add js files
		$this->minify->js(array('devository_script.js')); 

		// bool argument for rebuild css (false means skip rebuilding). 
		$this->data['devo_compressed_css'] = $this->minify->deploy_css();
		$this->data['devo_compressed_js'] = $this->minify->deploy_js(); 
		*/
		
		// Set template
		$this->template->set_theme($this->data['site_template']);
	}
}