<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|
|  Author : Oriza Sahputra
|  Custom post/page template library for Jooglo CMS
|  http://orizasahputra.com
|
*/

class Custom_page_template 
{
	var $ci;
	var $current_theme;
	
	/*
	Constructor
	*/
	public function custom_page_template()
	{
		// Get Codeigniter Instance
		$this->ci =& get_instance(); 
		
		// Get current theme
		$this->current_theme = $this->ci->mdl_options->get_options('template');
	}
	
	/*
	This is for check any custom template setted in a page
	false : the page is not using custom template or custom post/page file is gone
	true : the page is using custom template and the custom post/page file still exist
	*/
	public function is_this_use_custom($post_id)
	{	
		$custom_page_template = $this->ci->mdl_post->get_post_meta($post_id, 'post_template');
		$current_theme_path = 'jooglo/application/themes/'.$this->current_theme.'/views';
		
		if ($custom_page_template == 'default' || $custom_page_template == '')
		{
			return false;
		}
		else
		{
			// Check existing
			$check = $this->is_custom_template_still_exist($current_theme_path, $custom_page_template);
			
			// If file still exist allow custom custom page template works
			if ($check == true) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/*
	This method will return any custom page template in active theme
	*/	
	public function get_custom_page()
	{
		$current_theme_path = 'jooglo/application/themes/'.$this->current_theme.'/views/';
		return $this->get_custom_template($current_theme_path);
	}
	
	/*
	Begin private function for this class only
	*/
	private function count_custom_template($template_path, $type)
	{
		if ($handle = opendir($template_path)) 
		{
			$counter = 0;
			
			while (false !== ($entry = readdir($handle))) 
			{
				if ($type == 'page') {
					$param = '/my-page/';
				} else {
					$param = '/my-post/';
				}
				
				$check = preg_match($param, $entry); 
				
				if ($check == true)
				{
					$counter++;
				}
			}
			
			return $counter;	
			closedir($handle);
		}
	}
	
	/*
	This function checks physical file for custom theme with 'my-page' keyword
	*/
	private function get_custom_template($template_path)
	{	
		if ($handle = opendir($template_path))
		{
			$data = null;
			
			while (false !== ($entry = readdir($handle))) 
			{
				$param = '/my-page/';
				
				$check = preg_match($param, $entry); 
				
				if ($check == true)
				{
					$data[] = str_replace('.php','',$entry);
				}
			}
			
			return $data;
			
			closedir($handle);
		}
	}
	
	/*
	This method will check is the custom template file still exist in an active theme
	true : file still exist
	false : file is gone
	*/	
	private function is_custom_template_still_exist($template_path, $post_template_name)
	{
		if ($handle = opendir($template_path))
		{	
			$counter = 0;
			
			while (false !== ($entry = readdir($handle))) 
			{
				$param = '/'.$post_template_name.'/';
				
				$check = preg_match($param, $entry); 
				
				if ($check == true)
				{
					$counter++;
				}
			}
			
			if ($counter >= 1) {
				return true;
			}
			
			closedir($handle);
		}
	}
}